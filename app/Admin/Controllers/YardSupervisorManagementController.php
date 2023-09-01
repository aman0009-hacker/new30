<?php

namespace App\Admin\Controllers;

use App\Models\Records;
// use Auth;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Encore\Admin\Show;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Auth\Permission;
use Illuminate\Support\Facades\Log;
use App\Models\Yard;
use App\Models\AdminUser;




class YardSupervisorManagementController extends AdminController
{
  private $supervisorIdAdmin;
  /**
   * Title for current resource.
   *
   * @var string
   */
  protected $title = 'Yard Records';

  /**
   * Make a grid builder.
   *
   * @return Grid
   */
  protected function grid()
  {
    try {
      $grid = new Grid(new Records());
      // $grid->column('id', __('Id'));
      // $grid->column('supervisor_id', __('Supervisor id'));
      $grid->column('date', __('Date'));
      $grid->column('product', __('Product'));
      $grid->column('quantity', __('Quantity'));
      $grid->column('amount', __('Amount'));
      $grid->column('Total')->display(function () {
        return $this->quantity * $this->amount;
      });
      $grid->column('description', __('Description'));
      // $grid->column('created_at', __('Created at'))->display(function ($value) {
      //    // return Carbon::parse($value)->format('Y-m-d H:i:s');
      //    //return Carbon::parse($value)->format('d-m-Y');
      //   //  return Carbon::parse($value)->format('Y-m-d H:i');
      //    return Carbon::parse($value)->format('Y-m-d');
      // });
      // $grid->column('updated_at', __('Updated at'));
      $grid->filter(function ($filter) {
        // $filter->notIn('id', __('Id'));
        $filter->disableIdFilter();
        if(\Auth::user()->name==="Administrator")
        {
          $filter->like('supervisor_id',__('supervisor Name'))->select(AdminUser::pluck('name','id'));

        }
        // $filter->between('created_at', 'Select Date')->date('Y-m-d');
        $filter->between('created_at', 'Select Date')->date();
        //$filter->like('email', __('Email'));
        // $filter->column(1 / 2, function ($filter) {
        //   $filter->like('product', __('Product'));
        //  // $filter->like('description', __('Description'));
        // });
        // $filter->column(1 / 2, function ($filter) {
        //   $filter->like('quantity', __('Quantity'));
        //   // $filter->like('created_at', __('Created at'));
        //   $filter->between('created_at', 'Choose Date')->date();
        // });
      });
      $grid->actions(function ($actions) {
        $actions->disableEdit();
        $actions->disableView();
        $actions->disableDelete();
        //new code
        if (Admin::user()->can('create-post')) {
          Permission::check('create-post');
        }
        //new code
      });
      $grid->disableActions();
      //$grid->disableRowSelector();
      //new code
      //$grid->model()->where('supervisorid', Admin::user()->id)->orderBy('created_at', 'desc');
      if (Admin::user()->inRoles(['admin', 'administrator', 'Administartor'])) {
        // If user has one of the specified roles, show all records
        $yardRecords = Yard::where('supervisorid', Admin::user()->id)->value('yardplace');
        if (isset($yardRecords)) {
          $grid->setTitle($yardRecords." Yard");
        } else {
          $grid->setTitle("");
        }
        $grid->model()->orderBy('created_at', 'desc');
      } else {
        // Otherwise, show only records where supervisorid matches the login ID
        $yardRecords = Yard::where('supervisorid', Admin::user()->id)->value('yardplace');
        if (isset($yardRecords)) {
          $grid->setTitle($yardRecords." Yard");
        } else {
          $grid->setTitle("");
        }
        $grid->model()->where('supervisor_id', Admin::user()->id)->orderBy('created_at', 'desc');
      }
      //new code
      $grid->model()->orderBy('created_at', 'desc');
      return $grid;
    } catch (\Throwable $ex) {
      Log::info($ex->getMessage());
      return $grid;
    }
  }

  /**
   * Make a show builder.
   *
   * @param mixed $id
   * @return Show
   */
  protected function detail($id)
  {
    $show = new Show(Records::findOrFail($id));
    // $show->field('id', __('Id'));
    // $show->field('supervisor_id', __('Supervisor id'));
    $show->field('product', __('Product'));
    $show->field('quantity', __('Quantity'));
    $show->field('description', __('Description'));
    $show->field('created_at', __('Created at'));
    // $show->field('updated_at', __('Updated at'));
    return $show;
  }

  /**
   * Make a form builder.
   *
   * @return Form
   */
  //protected function form():\Illuminate\Contracts\View\View
  protected function form()
  {
    $form = new Form(new Records());
    //$form->number('supervisor_id', __('Supervisor id'));
    // $form->text('product', __('Product'));
    // $form->text('quantity', __('Quantity'));
    // $form->text('description', __('Description'));
    return $form;
    //return view('supervisor_records');
    //return view('supervisor');
    //return Route::redirect('');
  }
}