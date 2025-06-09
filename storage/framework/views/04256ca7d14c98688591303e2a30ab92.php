<?php if(Melorain::getAccess('UserController@active')): ?>
<?php $__env->startSection('header'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('content'); ?>
    <div class="example">
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">مدیریت</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.users.index')); ?>">مدیران سیستم</a></li>
                <li class="breadcrumb-item active" aria-current="page">لیست مدیران</li>
            </ol>
        </nav>
    </div>

    <?php if($message = Session::get('error') || $message = Session::get('success')): ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php if($message = Session::get('error')): ?>
                        <div class="note note-danger">
                            <strong>خطا!</strong>
                            <br>
                            <?php echo e($message); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($message = Session::get('success')): ?>
                        <div class="note note-success">
                            <?php echo e($message); ?>

                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <h6 class="card-title">لیست</h6>
                    </div>

                    <div class="table-responsive" style="min-height:400px;">

                        <table class="table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>
                                        عملیات
                                    </th>
                                    <th>
                                        نقش
                                    </th>
                                    <th>
                                        نام و نام خانوادگی
                                    </th>
                                    <th>
                                        ایمیل
                                    </th>
                                    <th>
                                        تاریخ ایجاد
                                    </th>
                                    <th>
                                        وضعیت
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="dropdown mb-2">
                                            <a type="button" id="dropdownMenuButton_<?php echo e($user->id); ?>" data-bs-toggle="dropdown" aria-haspopup="true"
                                               aria-expanded="false">
                                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_<?php echo e($user->id); ?>">
                                                
                                                <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('admin.users.edit',$user)); ?>"><i data-feather="edit-2" class="icon-sm me-2"></i> <span class="">ویرایش</span></a>
                                                <?php if(Auth::user()->role_id == 1): ?>
                                                <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_<?php echo e($user->id); ?>"><i data-feather="trash" class="icon-sm me-2"></i> <span class="">حذف</span></a>
                                                <?php endif; ?>

                                            </div>
                                        </div>

                                        <div class="modal fade" id="modal_<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form class="me-0 w-full" action="<?php echo e(route('admin.users.destroy',$user)); ?>" method="POST" >
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">تایید</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            از حذف این مورد مطئن هستید؟
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                            <button type="submit" class="btn btn-danger" >بله، حذف شود</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo e($user->role->title); ?>

                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.users.edit',$user)); ?>"><?php echo e($user->profile->full_name); ?></a>
                                    </td>
                                    <td>
                                        <?php echo e($user->email); ?>

                                    </td>
                                    <td>
                                        <?php echo e($user->created_at->jdate('Y/m/d')); ?>

                                    </td>
                                    <td>
                                        <?php if(Melorain::getAccess('UserController@active')): ?>
                                            <div class="form-check form-switch mb-2">
                                                <input type="checkbox" name="news_active" onchange="callActive(<?php echo e($user->id); ?>)" value="1" class="form-check-input" <?php if($user->activated == '1'): ?> checked <?php endif; ?> id="formSwitch<?php echo e($user->id); ?>">
                                                <label class="form-check-label" for="formSwitch<?php echo e($user->id); ?>">فعال</label>
                                            </div>
                                        <?php else: ?>
                                            <div class="form-check form-switch mb-2" title="دسترسی ندارید">
                                                <input type="checkbox" name="news_active" disabled class="form-check-input" <?php if($user->activated == '1'): ?> checked <?php endif; ?> id="formSwitch<?php echo e($user->id); ?>">
                                                <label class="form-check-label" for="formSwitch<?php echo e($user->id); ?>">فعال</label>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <?php echo e($users->links()); ?>

        </div>
<?php $__env->stopSection(); ?>
<?php if(Melorain::getAccess('UserController@active')): ?>
<?php $__env->startSection('footer'); ?>

    <script type="text/javascript">

        function callActive (id){
            let data;

            if(document.getElementById('formSwitch'+id).checked){
                data = {
                    id:id,
                    state:1,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
            } else {
                data = {
                    id:id,
                    state:0,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
            }

            $.post('<?php echo e(route('admin.users.active')); ?>',data,
                function(data, status){
                    if(data === '1' && status == 200){
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'با موفقیت تغییر کرد'
                        });
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'با موفقیت تغییر کرد'
                        });
                    }
                });
        }

    </script>
<?php $__env->stopSection(); ?>
<?php endif; ?>
<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\wamp64_new\www\mvprush\_git\resources\views/admin/users/index.blade.php ENDPATH**/ ?>