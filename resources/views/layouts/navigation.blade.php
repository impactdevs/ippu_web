<div class="container-fluid">

    <div id="two-column-menu">
    </div>
    <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('dashboard') }}">
                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
            </a>
        </li> <!-- end Dashboard Menu -->
        @if(Auth::user()->user_type == "Admin")
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/reminders/?status=Unread') }}">
                <i class="bx bx-bell"></i> <span data-key="t-members">Notifications</span> <span class="badge badge-pill bg-danger unread_notifications" data-key="t-hot">0</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/calender') }}">
                <i class=" ri-calendar-line"></i> <span data-key="t-members">Calender</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#sidebarEvents" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEvents">
                <i class="ri-apps-2-line"></i> <span data-key="t-apps">Events</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarEvents">
                <ul class="nav nav-sm flex-column">
                    @can('show event')
                    <li class="nav-item">
                        <a href="{{ url('admin/events') }}" class="nav-link"> Events
                        </a>
                    </li>
                    @endcan
                    @can('approve event attendence')
                    <li class="nav-item">
                        <a href="{{ url('admin/create_reminder/event') }}" class="nav-link"> Send Reminder
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>
        @can('members')
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/members') }}">
                <i class="las la-users"></i> <span data-key="t-members">Members</span>
            </a>
        </li>
        @endcan

        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/transactions') }}">
                <i class="las la-users"></i> <span data-key="t-members">Transactions</span>
            </a>
        </li>
    
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarCPDs" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                <i class="ri-layout-3-line"></i> <span data-key="t-layouts">CPDs</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarCPDs">
                <ul class="nav nav-sm flex-column">
                    @can('show CPD')
                    <li class="nav-item">
                        <a href="{{ url('admin/cpds') }}" class="nav-link"> CPDs
                        </a>
                    </li>
                    @endcan
                    @can('approve CPD attendence')
                    <li class="nav-item">
                        <a href="{{ url('admin/create_reminder/cpd') }}" class="nav-link"> Send Reminder
                        </a>
                    </li>
                    @endcan
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
                        <a href="{{ url('admin/customers') }}" class="nav-link"> Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/vendors') }}" class="nav-link"> Vendors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/proposals') }}" class="nav-link"> Quotations
                        </a>
                    </li>
                    @can('invoice')
                    <li class="nav-item">
                        <a href="#sidebarSignUp" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignUp" data-key="t-signup"> Incomes
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSignUp">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('admin/invoices') }}" class="nav-link" data-key="t-cover"> Invoices
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="auth-signup-cover.html" class="nav-link" data-key="t-cover"> Cash Sales
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="{{ url('admin/credit_notes') }}" class="nav-link" data-key="t-cover"> Credit Notes
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcan
                    @can('expense')
                    <li class="nav-item">
                        <a href="#sidebarResetPass" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarResetPass" data-key="t-password-reset">
                            Expenses
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarResetPass">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('admin/bills') }}" class="nav-link" data-key="t-basic">
                                    Bills </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-pass-reset-cover.html" class="nav-link" data-key="t-cover">
                                    Payments </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="auth-pass-reset-cover.html" class="nav-link" data-key="t-cover">
                                    Debit Note </a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>
                    @endcan
                    @can('inventory')
                    <li class="nav-item">
                        <a href="#sidebarchangePass" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarchangePass" data-key="t-password-create">
                            Inventory
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarchangePass">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('admin/productservice') }}" class="nav-link" data-key="t-basic">
                                    Products / Services </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/productstock') }}" class="nav-link" data-key="t-cover">
                                    Stocking </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endcan

                    {{-- <li class="nav-item">
                        <a href="#sidebarLockScreen" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLockScreen" data-key="t-lock-screen">
                            Advanced
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLockScreen">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="auth-lockscreen-cover.html" class="nav-link" data-key="t-cover">
                                    Ledgers </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-lockscreen-basic.html" class="nav-link" data-key="t-basic">
                                    Profit & Loss </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-lockscreen-cover.html" class="nav-link" data-key="t-cover">
                                    Trial Balance </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-lockscreen-cover.html" class="nav-link" data-key="t-cover">
                                    Balance Sheet </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-lockscreen-cover.html" class="nav-link" data-key="t-cover">
                                    Chart Of Accounts </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#sidebarLogout" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLogout" data-key="t-logout"> Settings
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLogout">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('currencies') }}" class="nav-link" data-key="t-basic"> Currencies
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-logout-cover.html" class="nav-link" data-key="t-cover"> Categories
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('uoms') }}" class="nav-link" data-key="t-cover"> UOM
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarSuccessMsg" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSuccessMsg" data-key="t-success-message"> EFRIS
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSuccessMsg">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('efris') }}" class="nav-link" data-key="t-basic">
                                    Settings </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-success-msg-cover.html" class="nav-link" data-key="t-cover">
                                    Invoices </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-success-msg-cover.html" class="nav-link" data-key="t-cover">
                                    Stock </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarTwoStep" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTwoStep" data-key="t-two-step-verification"> Two Step Verification
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarTwoStep">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="auth-twostep-basic.html" class="nav-link" data-key="t-basic"> Basic
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-twostep-cover.html" class="nav-link" data-key="t-cover"> Cover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#sidebarErrors" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarErrors" data-key="t-errors"> Errors
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarErrors">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="auth-404-basic.html" class="nav-link" data-key="t-404-basic"> 404
                                    Basic </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-404-cover.html" class="nav-link" data-key="t-404-cover"> 404
                                    Cover </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-404-alt.html" class="nav-link" data-key="t-404-alt"> 404 Alt
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-500.html" class="nav-link" data-key="t-500"> 500 </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-offline.html" class="nav-link" data-key="t-offline-page"> Offline Page </a>
                                </li>
                            </ul>
                        </div>
                    </li> --}}
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                <i class="ri-pages-line"></i> <span data-key="t-pages">HRM</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarPages">
                <ul class="nav nav-sm flex-column">
                    @can('hrm')
                    <li class="nav-item">
                        <a href="{{ url('admin/jobs') }}" class="nav-link" data-key="t-starter"> Jobs </a>
                    </li>
                    @endcan
                    {{-- <li class="nav-item">
                        <a href="#sidebarProfile" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProfile" data-key="t-profile"> Profile
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarProfile">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="pages-profile.html" class="nav-link" data-key="t-simple-page">
                                    Simple Page </a>
                                </li>
                                <li class="nav-item">
                                    <a href="pages-profile-settings.html" class="nav-link" data-key="t-settings"> Settings </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="pages-team.html" class="nav-link" data-key="t-team"> Team </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-timeline.html" class="nav-link" data-key="t-timeline"> Timeline </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-faqs.html" class="nav-link" data-key="t-faqs"> FAQs </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-pricing.html" class="nav-link" data-key="t-pricing"> Pricing </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-gallery.html" class="nav-link" data-key="t-gallery"> Gallery </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-maintenance.html" class="nav-link" data-key="t-maintenance"> Maintenance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-coming-soon.html" class="nav-link" data-key="t-coming-soon"> Coming Soon
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-sitemap.html" class="nav-link" data-key="t-sitemap"> Sitemap </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-search-results.html" class="nav-link" data-key="t-search-results"> Search Results </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-privacy-policy.html" class="nav-link"><span data-key="t-privacy-policy">Privacy Policy</span> <span class="badge badge-pill bg-success" data-key="t-new">New</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="pages-term-conditions.html" class="nav-link"><span data-key="t-term-conditions">Term & Conditions</span> <span class="badge badge-pill bg-success" data-key="t-new">New</span></a>
                    </li> --}}
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarLanding" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLanding">
                <i class="ri-rocket-line"></i> <span data-key="t-landing">CRM</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarLanding">
                <ul class="nav nav-sm flex-column">
                    @can('leads')
                    <li class="nav-item">
                        <a href="{{ url('admin/leads') }}" class="nav-link" data-key="t-one-page"> Leads </a>
                    </li>
                    @endcan
                    @can('deals')
                    <li class="nav-item">
                        <a href="{{ url('admin/deals') }}" class="nav-link" data-key="t-nft-landing"> Deals </a>
                    </li>
                    @endcan
                    @can('form builder')
                    <li class="nav-item">
                        <a href="{{ url('admin/form_builders') }}" class="nav-link"><span data-key="t-job">Form Builder</span></a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="{{ url('chat') }}" aria-expanded="false" aria-controls="sidebarUI">
                <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Chats</span>
            </a>
        </li>

        @can('communications')
        <li class="nav-item">
            <a href="#sidebarCommunication" class="nav-link menu-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCommunication">
                 <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Communications</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarCommunication">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{ url('communications') }}" class="nav-link" data-key="t-sweet-alerts">General</a>
                    </li>
                     <li class="nav-item">
                        <a href="{{ url('admin/sms') }}" class="nav-link" data-key="t-sweet-alerts">SMS</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/newsletter') }}" class="nav-link" data-key="t-sweet-alerts">Newsletter</a>
                    </li>
                </ul>
            </div>
        </li>
        @endcan

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                <i class="ri-stack-line"></i> <span data-key="t-advance-ui">Reports</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarAdvanceUI">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{ url('admin/members_report') }}" class="nav-link" data-key="t-sweet-alerts">Members</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/cpds_report') }}" class="nav-link" data-key="t-nestable-list">Cpds</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/events_report') }}" class="nav-link" data-key="t-nestable-list">Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/payments_report') }}" class="nav-link" data-key="t-animation">Payments</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/points_report') }}" class="nav-link" data-key="t-tour">Points</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('admin/account_types_report') }}" class="nav-link" data-key="t-swiper-slider">Account Types</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="advance-ui-ratings.html" class="nav-link" data-key="t-ratings">Ratings</a>
                    </li>
                    <li class="nav-item">
                        <a href="advance-ui-highlight.html" class="nav-link" data-key="t-highlight">Highlight</a>
                    </li>
                    <li class="nav-item">
                        <a href="advance-ui-scrollspy.html" class="nav-link" data-key="t-scrollSpy">ScrollSpy</a>
                    </li> --}}
                </ul>
            </div>
        </li>
        @can('audit trail')
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/audit-trail') }}">
                <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Audit Trail</span>
            </a>
        </li>
        @endcan

        @can('settings')
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
                                    <a href="{{ url('admin/account_types') }}" class="nav-link" data-key="t-level-3.1"> Account Types
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/product-category') }}" class="nav-link" data-key="t-level-3.2"> Categories
                                    </a>
                                </li>
                                 <li class="nav-item">
                                    <a href="{{ url('admin/product-unit') }}" class="nav-link" data-key="t-level-3.2"> Units
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/taxes') }}" class="nav-link" data-key="t-level-3.2"> Taxes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/users') }}" class="nav-link" data-key="t-level-3.2"> Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/custom-fields') }}" class="nav-link" data-key="t-level-3.2"> Custom Fields
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
                                    <a href="{{ url('admin/pipelines') }}" class="nav-link" data-key="t-level-3.1"> Pipelines
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/lead_stages') }}" class="nav-link" data-key="t-level-3.2"> Lead Stages
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/stages') }}" class="nav-link" data-key="t-level-3.2"> Deal Stages
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/sources') }}" class="nav-link" data-key="t-level-3.2"> Sources
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/labels') }}" class="nav-link" data-key="t-level-3.2"> Labels
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/contract_types') }}" class="nav-link" data-key="t-level-3.2"> Contract Types
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </li>
        @endcan
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{ url('education') }}">
                <i class="ri-compasses-2-line"></i> <span data-key="t-icons">Education Background</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('chat') }}">
                <i class="ri-messenger-fill"></i> <span data-key="t-icons">Chat</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('communications') }}" aria-expanded="false" aria-controls="sidebarUI">
                <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Communications</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('get-newsletters') }}" aria-expanded="false" aria-controls="sidebarUI">
                    <i class=" ri-message-fill"></i> <span data-key="t-base-ui">Newsletter</span>
                </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('experiences') }}">
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
                        <a href="{{ url('all_events') }}" class="nav-link" data-key="t-basic-tables">All Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('upcoming_events') }}" class="nav-link" data-key="t-grid-js">Upcoming Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('attended_events') }}" class="nav-link" data-key="t-list-js">My Events</a>
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
                        <a href="{{ url('all_cpds') }}" class="nav-link">
                            All CPDs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('upcoming_cpds') }}" class="nav-link" data-key="t-echarts"> Upcoming CPDs</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('attended_cpds') }}" class="nav-link" data-key="t-chartjs"> My CPDs </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link " href="{{ url('jobs') }}">
                <i class="ri-share-line"></i> <span data-key="t-multi-level">Jobs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="{{ url('who-we-are') }}">
                <i class="ri-stack-line"></i> <span data-key="t-multi-level">Who We Are</span>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link " href="{{ url('core-values') }}">
                <i class="ri-rocket-line"></i> <span data-key="t-multi-level">Our Core Values</span>
            </a>
        </li>
        @endif

    </ul>
</div>
