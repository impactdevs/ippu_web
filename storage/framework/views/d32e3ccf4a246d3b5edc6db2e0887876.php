<div class="container-fluid">

    <div id="two-column-menu">
    </div>
    <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('dashboard')); ?>">
                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
            </a>
        </li> <!-- end Dashboard Menu -->
        <?php if(Auth::user()->user_type == "Admin"): ?>
        <li class="nav-item">
            <a class="nav-link" href="#sidebarEvents" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEvents">
                <i class="ri-apps-2-line"></i> <span data-key="t-apps">Events</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarEvents">
                <ul class="nav nav-sm flex-column">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show event')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/events')); ?>" class="nav-link"> Events
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve event attendence')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/create_reminder/event')); ?>" class="nav-link"> Send Reminder
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('members')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('admin/members')); ?>">
                <i class="las la-users"></i> <span data-key="t-members">Members</span>
            </a>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarCPDs" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                <i class="ri-layout-3-line"></i> <span data-key="t-layouts">CPDs</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarCPDs">
                <ul class="nav nav-sm flex-column">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show CPD')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/cpds')); ?>" class="nav-link"> CPDs
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve CPD attendence')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/create_reminder/cpd')); ?>" class="nav-link"> Send Reminder
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li> <!-- end Dashboard Menu -->

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                <i class="ri-pie-chart-line"></i> <span data-key="t-authentication">Accounting</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarAuth">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/customers')); ?>" class="nav-link"> Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/vendors')); ?>" class="nav-link"> Vendors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/proposals')); ?>" class="nav-link"> Quotations
                        </a>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoice')): ?>
                    <li class="nav-item">
                        <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignUp" data-key="t-signup"> Incomes
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSignUp">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/invoices')); ?>" class="nav-link" data-key="t-cover"> Invoices
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/credit_notes')); ?>" class="nav-link" data-key="t-cover"> Credit Notes
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense')): ?>
                    <li class="nav-item">
                        <a href="#sidebarResetPass" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarResetPass" data-key="t-password-reset">
                            Expenses
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarResetPass">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/bills')); ?>" class="nav-link" data-key="t-basic">
                                    Bills </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-pass-reset-cover.html" class="nav-link" data-key="t-cover">
                                    Payments </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inventory')): ?>
                    <li class="nav-item">
                        <a href="#sidebarchangePass" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarchangePass" data-key="t-password-create">
                            Inventory
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarchangePass">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/productservice')); ?>" class="nav-link" data-key="t-basic">
                                    Products / Services </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/productstock')); ?>" class="nav-link" data-key="t-cover">
                                    Stocking </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                <i class="ri-pages-line"></i> <span data-key="t-pages">HRM</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarPages">
                <ul class="nav nav-sm flex-column">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/jobs')); ?>" class="nav-link" data-key="t-starter"> Jobs </a>
                    </li>
                    <?php endif; ?>
                    
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLanding">
                <i class="ri-rocket-line"></i> <span data-key="t-landing">CRM</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarLanding">
                <ul class="nav nav-sm flex-column">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leads')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/leads')); ?>" class="nav-link" data-key="t-one-page"> Leads </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deals')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/deals')); ?>" class="nav-link" data-key="t-nft-landing"> Deals </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('form builder')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/form_builders')); ?>" class="nav-link"><span data-key="t-job">Form Builder</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('chat')); ?>" aria-expanded="false" aria-controls="sidebarUI">
                <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Chats</span>
            </a>
        </li>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('communications')): ?>
        <li class="nav-item">
            <a href="#sidebarCommunication" class="nav-link menu-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCommunication">
                 <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Communications</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarCommunication">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(url('communications')); ?>" class="nav-link" data-key="t-sweet-alerts">General</a>
                    </li>
                     <li class="nav-item">
                        <a href="<?php echo e(url('admin/sms')); ?>" class="nav-link" data-key="t-sweet-alerts">SMS</a>
                    </li>
                </ul>
            </div>
        </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                <i class="ri-stack-line"></i> <span data-key="t-advance-ui">Reports</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarAdvanceUI">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/members_report')); ?>" class="nav-link" data-key="t-sweet-alerts">Members</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/cpds_report')); ?>" class="nav-link" data-key="t-nestable-list">Cpds</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/events_report')); ?>" class="nav-link" data-key="t-nestable-list">Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/payments_report')); ?>" class="nav-link" data-key="t-animation">Payments</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('admin/points_report')); ?>" class="nav-link" data-key="t-tour">Points</a>
                    </li>
                    
                </ul>
            </div>
        </li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('audit trail')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('admin/audit-trail')); ?>">
                <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Audit Trail</span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings')): ?>
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                <i class="ri-settings-3-fill"></i> <span data-key="t-widgets">Settings</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarSettings">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="#sidebarSettingsAccounts" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettingsAccounts" data-key="t-level-2.2"> Accounting
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSettingsAccounts">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/account_types')); ?>" class="nav-link" data-key="t-level-3.1"> Account Types
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/product-category')); ?>" class="nav-link" data-key="t-level-3.2"> Categories
                                    </a>
                                </li>
                                 <li class="nav-item">
                                    <a href="<?php echo e(url('admin/product-unit')); ?>" class="nav-link" data-key="t-level-3.2"> Units
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/taxes')); ?>" class="nav-link" data-key="t-level-3.2"> Taxes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/users')); ?>" class="nav-link" data-key="t-level-3.2"> Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/custom-fields')); ?>" class="nav-link" data-key="t-level-3.2"> Custom Fields
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarSettingsCRM" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettingsCRM" data-key="t-level-2.2"> CRM
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSettingsCRM">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/pipelines')); ?>" class="nav-link" data-key="t-level-3.1"> Pipelines
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/lead_stages')); ?>" class="nav-link" data-key="t-level-3.2"> Lead Stages
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/stages')); ?>" class="nav-link" data-key="t-level-3.2"> Deal Stages
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/sources')); ?>" class="nav-link" data-key="t-level-3.2"> Sources
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/labels')); ?>" class="nav-link" data-key="t-level-3.2"> Labels
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo e(url('admin/contract_types')); ?>" class="nav-link" data-key="t-level-3.2"> Contract Types
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </li>
        <?php endif; ?>
        <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('education')); ?>">
                <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Education Background</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('chat')); ?>">
                <i class="ri-messenger-fill"></i> <span data-key="t-icons">Chat</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('communications')); ?>" aria-expanded="false" aria-controls="sidebarUI">
                <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Communications</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(url('experiences')); ?>">
                <i class="ri-map-pin-line"></i> <span data-key="t-maps">Work Experience</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                <i class="ri-layout-grid-line"></i> <span data-key="t-tables">Events</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarTables">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(url('all_events')); ?>" class="nav-link" data-key="t-basic-tables">All Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('upcoming_events')); ?>" class="nav-link" data-key="t-grid-js">Upcoming Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('attended_events')); ?>" class="nav-link" data-key="t-list-js">My Events</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCharts">
                <i class="ri-pie-chart-line"></i> <span data-key="t-charts">CPD</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarCharts">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(url('all_cpds')); ?>" class="nav-link">
                            All CPDs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('upcoming_cpds')); ?>" class="nav-link" data-key="t-echarts"> Upcoming CPDs</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(url('attended_cpds')); ?>" class="nav-link" data-key="t-chartjs"> My CPDs </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link " href="<?php echo e(url('jobs')); ?>">
                <i class="ri-share-line"></i> <span data-key="t-multi-level">Jobs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="<?php echo e(url('who-we-are')); ?>">
                <i class="ri-stack-line"></i> <span data-key="t-multi-level">Who We Are</span>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link " href="<?php echo e(url('core-values')); ?>">
                <i class="ri-rocket-line"></i> <span data-key="t-multi-level">Our Core Values</span>
            </a>
        </li>
        <?php endif; ?>

    </ul>
</div>
<?php /**PATH /var/www/html/ippu/resources/views/layouts/navigation.blade.php ENDPATH**/ ?>