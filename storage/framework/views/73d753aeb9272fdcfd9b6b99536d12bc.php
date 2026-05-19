<?php if (isset($component)) { $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.layouts.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::layouts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.title'); ?>
     <?php $__env->endSlot(); ?>

    <v-events>
        <div class="flex justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.title'); ?>
            </p>

            <div class="flex items-center gap-x-2.5">
                <!-- Create Button -->
                <?php if(bouncer()->hasPermission('marketing.communications.events.create')): ?>
                    <div class="primary-button">
                        <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.create-btn'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- DataGrid Shimmer -->
        <?php if (isset($component)) { $__componentOriginal43b413ed358fbcef7e1b7fbfaabac910 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal43b413ed358fbcef7e1b7fbfaabac910 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal43b413ed358fbcef7e1b7fbfaabac910)): ?>
<?php $attributes = $__attributesOriginal43b413ed358fbcef7e1b7fbfaabac910; ?>
<?php unset($__attributesOriginal43b413ed358fbcef7e1b7fbfaabac910); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal43b413ed358fbcef7e1b7fbfaabac910)): ?>
<?php $component = $__componentOriginal43b413ed358fbcef7e1b7fbfaabac910; ?>
<?php unset($__componentOriginal43b413ed358fbcef7e1b7fbfaabac910); ?>
<?php endif; ?>
    </v-events>

    <?php if (! $__env->hasRenderedOnce('a8e18904-b527-4cff-9e82-962825c161ff')): $__env->markAsRenderedOnce('a8e18904-b527-4cff-9e82-962825c161ff');
$__env->startPush('scripts'); ?>
        <script
            type="text/x-template"
            id="v-events-template"
        >
            <div class="flex justify-between gap-4 max-sm:flex-wrap">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.title'); ?>
                </p>

                <div class="flex items-center gap-x-2.5">
                    <!-- Create Button -->
                    <?php if(bouncer()->hasPermission('marketing.communications.events.create')): ?>
                        <div
                            class="primary-button"
                            @click="selectedEvents={}; $refs.emailEvents.toggle()"
                        >
                            <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.create-btn'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo view_render_event('bagisto.admin.marketing.communications.events.list.before'); ?>


            <?php if (isset($component)) { $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.datagrid.ssr','data' => ['datagridPayload' => $datagridPayload,'src' => route('superadmin.marketing.communications.events.index'),'ref' => 'datagrid']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::datagrid.ssr'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['datagrid-payload' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($datagridPayload),'src' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('superadmin.marketing.communications.events.index')),'ref' => 'datagrid']); ?>
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <?php if (isset($component)) { $__componentOriginal8e67d22a82a577a821cc12b998c2d9e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.shimmer.datagrid.table.body','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::shimmer.datagrid.table.body'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2)): ?>
<?php $attributes = $__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2; ?>
<?php unset($__attributesOriginal8e67d22a82a577a821cc12b998c2d9e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e67d22a82a577a821cc12b998c2d9e2)): ?>
<?php $component = $__componentOriginal8e67d22a82a577a821cc12b998c2d9e2; ?>
<?php unset($__componentOriginal8e67d22a82a577a821cc12b998c2d9e2); ?>
<?php endif; ?>
                    </template>

                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                    >
                        <!-- Id -->
                        <p>{{ record.id }}</p>

                        <!-- Status -->
                        <p>{{ record.name }}</p>

                        <!-- Email -->
                        <p>{{ record.date }}</p>

                            <!-- Actions -->
                            <?php if(
                                bouncer()->hasPermission('marketing.communications.events.edit')
                                || bouncer()->hasPermission('marketing.communications.events.delete')
                            ): ?>
                                <div class="flex justify-end">
                                    <?php if(bouncer()->hasPermission('marketing.communications.events.edit')): ?>
                                        <a @click="id=1; editModal(record.actions.find(action => action.index === 'edit')?.url)">
                                            <span
                                                :class="record.actions.find(action => action.index === 'edit')?.icon"
                                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :title="record.actions.find(action => action.title === '<?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.datagrid.edit'); ?>')?.title"
                                            >
                                            </span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(bouncer()->hasPermission('marketing.communications.events.delete')): ?>
                                        <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                            <span
                                                :class="record.actions.find(action => action.index === 'delete')?.icon"
                                                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :title="record.actions.find(action => action.title === '<?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.datagrid.delete'); ?>')?.title"
                                            >
                                            </span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </template>
                </template>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0)): ?>
<?php $attributes = $__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0; ?>
<?php unset($__attributesOriginald3fcfed31d8a223d9284f5993c9ecea0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald3fcfed31d8a223d9284f5993c9ecea0)): ?>
<?php $component = $__componentOriginald3fcfed31d8a223d9284f5993c9ecea0; ?>
<?php unset($__componentOriginald3fcfed31d8a223d9284f5993c9ecea0); ?>
<?php endif; ?>

            <?php echo view_render_event('bagisto.admin.marketing.communications.events.list.after'); ?>


            <?php echo view_render_event('bagisto.admin.marketing.communications.events.create.before'); ?>


            <!-- Email Events form -->
            <?php if (isset($component)) { $__componentOriginal846e584e6d28d684de3f16eae7bf519e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal846e584e6d28d684de3f16eae7bf519e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.index','data' => ['vSlot' => '{ meta, errors, handleSubmit }','as' => 'div','ref' => 'modalForm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['v-slot' => '{ meta, errors, handleSubmit }','as' => 'div','ref' => 'modalForm']); ?>
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="eventCreateForm"
                >

                    <?php echo view_render_event('bagisto.admin.marketing.communications.events.create_form_controls.before'); ?>


                    <?php if (isset($component)) { $__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.modal.index','data' => ['ref' => 'emailEvents']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ref' => 'emailEvents']); ?>
                        <!-- Modal Header -->
                         <?php $__env->slot('header', null, []); ?> 
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="selectedEvents"
                            >
                                <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.create.title'); ?>
                            </p>

                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                <?php echo app('translator')->get('superadmin::app.settings.users.index.create.title'); ?>
                            </p>
                         <?php $__env->endSlot(); ?>

                        <!-- Modal Content -->
                         <?php $__env->slot('content', null, []); ?> 
                            <!-- ID -->
                            <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'hidden','name' => 'id','vModel' => 'selectedEvents.id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'hidden','name' => 'id','v-model' => 'selectedEvents.id']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $attributes = $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $component = $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>

                            <!-- Event Name -->
                            <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if (isset($component)) { $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                    <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.create.name'); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $attributes = $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $component = $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'text','name' => 'name','rules' => 'required','value' => old('name'),'vModel' => 'selectedEvents.name','label' => trans('superadmin::app.marketing.communications.events.index.create.name'),'placeholder' => trans('superadmin::app.marketing.communications.events.index.create.name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'name','rules' => 'required','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name')),'v-model' => 'selectedEvents.name','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.marketing.communications.events.index.create.name')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.marketing.communications.events.index.create.name'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $attributes = $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $component = $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal7d00c14826cd26beafba9f36875ab882 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d00c14826cd26beafba9f36875ab882 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $attributes = $__attributesOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__attributesOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $component = $__componentOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__componentOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $attributes = $__attributesOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $component = $__componentOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__componentOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>

                            <!-- Event Description -->
                            <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                                <?php if (isset($component)) { $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                    <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.create.description'); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $attributes = $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $component = $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'textarea','class' => 'h-[100px]','id' => 'description','name' => 'description','rules' => 'required','value' => old('description'),'vModel' => 'selectedEvents.description','label' => trans('superadmin::app.marketing.communications.events.index.create.description')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'textarea','class' => 'h-[100px]','id' => 'description','name' => 'description','rules' => 'required','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('description')),'v-model' => 'selectedEvents.description','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.marketing.communications.events.index.create.description'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $attributes = $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $component = $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal7d00c14826cd26beafba9f36875ab882 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d00c14826cd26beafba9f36875ab882 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'description']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'description']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $attributes = $__attributesOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__attributesOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $component = $__componentOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__componentOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $attributes = $__attributesOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $component = $__componentOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__componentOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>

                            <!-- Event Date -->
                            <?php if (isset($component)) { $__componentOriginal7f7a55e7974955e628f482d5ed25a914 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f7a55e7974955e628f482d5ed25a914 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.index','data' => ['class' => '!mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '!mb-0']); ?>
                                <?php if (isset($component)) { $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.label','data' => ['class' => 'required']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'required']); ?>
                                    <?php echo app('translator')->get('superadmin::app.marketing.communications.events.index.create.date'); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $attributes = $__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__attributesOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462)): ?>
<?php $component = $__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462; ?>
<?php unset($__componentOriginal1d4aecbe561d87e1ed01e1c300ac1462); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.control','data' => ['type' => 'date','class' => 'cursor-pointer','name' => 'date','rules' => 'required','value' => old('date'),'vModel' => 'selectedEvents.date','label' => trans('superadmin::app.marketing.communications.events.index.create.date'),'placeholder' => trans('superadmin::app.marketing.communications.events.index.create.date')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','class' => 'cursor-pointer','name' => 'date','rules' => 'required','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('date')),'v-model' => 'selectedEvents.date','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.marketing.communications.events.index.create.date')),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.marketing.communications.events.index.create.date'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $attributes = $__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__attributesOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552)): ?>
<?php $component = $__componentOriginal5a7b1c9c981038a8e4a92c09220bd552; ?>
<?php unset($__componentOriginal5a7b1c9c981038a8e4a92c09220bd552); ?>
<?php endif; ?>

                                <?php if (isset($component)) { $__componentOriginal7d00c14826cd26beafba9f36875ab882 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7d00c14826cd26beafba9f36875ab882 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.form.control-group.error','data' => ['controlName' => 'date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::form.control-group.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['control-name' => 'date']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $attributes = $__attributesOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__attributesOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7d00c14826cd26beafba9f36875ab882)): ?>
<?php $component = $__componentOriginal7d00c14826cd26beafba9f36875ab882; ?>
<?php unset($__componentOriginal7d00c14826cd26beafba9f36875ab882); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $attributes = $__attributesOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__attributesOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f7a55e7974955e628f482d5ed25a914)): ?>
<?php $component = $__componentOriginal7f7a55e7974955e628f482d5ed25a914; ?>
<?php unset($__componentOriginal7f7a55e7974955e628f482d5ed25a914); ?>
<?php endif; ?>
                         <?php $__env->endSlot(); ?>

                        <!-- Modal Footer -->
                         <?php $__env->slot('footer', null, []); ?> 
                            <!-- Save Button -->
                            <?php if (isset($component)) { $__componentOriginal3b6b23477c69b0901e72ab03d3729d36 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b6b23477c69b0901e72ab03d3729d36 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'superadmin::components.button.index','data' => ['buttonType' => 'button','class' => 'primary-button','title' => trans('superadmin::app.marketing.communications.events.index.create.save-btn'),':loading' => 'isLoading',':disabled' => 'isLoading']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('superadmin::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['button-type' => 'button','class' => 'primary-button','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(trans('superadmin::app.marketing.communications.events.index.create.save-btn')),':loading' => 'isLoading',':disabled' => 'isLoading']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b6b23477c69b0901e72ab03d3729d36)): ?>
<?php $attributes = $__attributesOriginal3b6b23477c69b0901e72ab03d3729d36; ?>
<?php unset($__attributesOriginal3b6b23477c69b0901e72ab03d3729d36); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b6b23477c69b0901e72ab03d3729d36)): ?>
<?php $component = $__componentOriginal3b6b23477c69b0901e72ab03d3729d36; ?>
<?php unset($__componentOriginal3b6b23477c69b0901e72ab03d3729d36); ?>
<?php endif; ?>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6)): ?>
<?php $attributes = $__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6; ?>
<?php unset($__attributesOriginal364c1c8fde15bdfd937023c1b0cc1ed6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6)): ?>
<?php $component = $__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6; ?>
<?php unset($__componentOriginal364c1c8fde15bdfd937023c1b0cc1ed6); ?>
<?php endif; ?>

                    <?php echo view_render_event('bagisto.admin.marketing.communications.events.create_form_controls.after'); ?>


                </form>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal846e584e6d28d684de3f16eae7bf519e)): ?>
<?php $attributes = $__attributesOriginal846e584e6d28d684de3f16eae7bf519e; ?>
<?php unset($__attributesOriginal846e584e6d28d684de3f16eae7bf519e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal846e584e6d28d684de3f16eae7bf519e)): ?>
<?php $component = $__componentOriginal846e584e6d28d684de3f16eae7bf519e; ?>
<?php unset($__componentOriginal846e584e6d28d684de3f16eae7bf519e); ?>
<?php endif; ?>

            <?php echo view_render_event('bagisto.admin.marketing.communications.events.create.after'); ?>


        </script>

        <script type="module">
            window.app.component('v-events', {
                template: '#v-events-template',

                data() {
                    return {
                        selectedEvents: {},

                        isLoading: false,
                    }
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        this.isLoading = true;

                        let formData = new FormData(this.$refs.eventCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "<?php echo e(route('superadmin.marketing.communications.events.update')); ?>" : "<?php echo e(route('superadmin.marketing.communications.events.store')); ?>", formData)
                            .then((response) => {
                                this.isLoading = false;

                                this.$refs.emailEvents.toggle();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                if (response.data.id) {
                                    this.selectedEvents = response.data;

                                    this.$refs.emailEvents.toggle();
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'error', message: response.data.message });
                                }
                            })
                            .catch(error => this.$emitter.emit('add-flash', {
                                type: 'error', message: error.response.data.message
                            }));
                    },
                }
            })
        </script>
    <?php $__env->stopPush(); endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $attributes = $__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__attributesOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491)): ?>
<?php $component = $__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491; ?>
<?php unset($__componentOriginal0c35c2591d22d4fd3701fd00e3a3e491); ?>
<?php endif; ?>
<?php /**PATH /Users/hhtraders/tiktokshop/packages/Webkul/SuperAdmin/src/Providers/../Resources/views/marketing/communications/events/index.blade.php ENDPATH**/ ?>