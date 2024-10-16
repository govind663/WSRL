<div class="right-sidebar">
    <div class="sidebar-title">
        <h3 class="weight-600 font-16 text-blue">
            Layout Settings
            <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
        </h3>
        <div class="close-sidebar" data-toggle="right-sidebar-close">
            <i class="icon-copy ion-close-round"></i>
        </div>
    </div>
    <div class="right-sidebar-body customscroll">
        <div class="right-sidebar-body-content">
            <h4 class="weight-600 font-18 pb-10">Header Background</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
            </div>

            <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
                <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
            </div>

            <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
            <div class="sidebar-radio-group pb-10 mb-10">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon"
                        class="custom-control-input" value="icon-style-1" checked="" />
                    <label class="custom-control-label" for="sidebaricon-1"><i
                            class="fa fa-angle-down"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon"
                        class="custom-control-input" value="icon-style-2" />
                    <label class="custom-control-label" for="sidebaricon-2"><i
                            class="ion-plus-round"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon"
                        class="custom-control-input" value="icon-style-3" />
                    <label class="custom-control-label" for="sidebaricon-3"><i
                            class="fa fa-angle-double-right"></i></label>
                </div>
            </div>

            <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
            <div class="sidebar-radio-group pb-30 mb-10">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-1" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-1" checked="" />
                    <label class="custom-control-label" for="sidebariconlist-1"><i
                            class="ion-minus-round"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-2" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-2" />
                    <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                            aria-hidden="true"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-3" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-3" />
                    <label class="custom-control-label" for="sidebariconlist-3"><i
                            class="dw dw-check"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-4" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-4" checked="" />
                    <label class="custom-control-label" for="sidebariconlist-4"><i
                            class="icon-copy dw dw-next-2"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-5" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-5" />
                    <label class="custom-control-label" for="sidebariconlist-5"><i
                            class="dw dw-fast-forward-1"></i></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="sidebariconlist-6" name="menu-list-icon"
                        class="custom-control-input" value="icon-list-style-6" />
                    <label class="custom-control-label" for="sidebariconlist-6"><i
                            class="dw dw-next"></i></label>
                </div>
            </div>

            <div class="reset-options pt-30 text-center">
                <button class="btn btn-danger" id="reset-settings">
                    Reset Settings
                </button>
            </div>
        </div>
    </div>
</div>

<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/vendors/images/deskapp-logo.svg') }}" alt="" class="dark-logo" />
            <img src="{{ asset('assets/vendors/images/deskapp-logo-white.svg') }}" alt="" class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'admin.dashboard') ? 'active' : '' }}">
                        <span class="micon bi bi-house"></span>
                        <span class="mtext">Home</span>
                    </a>
                </li>

                @can('role-list')
                <li>
                    <a href="{{ route('roles.index') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'roles.index') || ($currentRoute === 'roles.create') || ($currentRoute === 'roles.edit') ? 'active' : '' }}">
                        <span class="micon bi bi-diagram-3"></span>
                        <span class="mtext">Manage Role</span>
                    </a>
                </li>
                @endcan

                @can('user-list')
                <li>
                    <a href="{{ route('users.index') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'users.index') || ($currentRoute === 'users.create') || ($currentRoute === 'users.edit') ? 'active' : '' }}">
                        <span class="micon bi bi-person"></span>
                        <span class="mtext">Manage User</span>
                    </a>
                </li>
                @endcan

                @can('distributor-list')
                <li>
                    <a href="{{ route('distributor.index') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'distributor.index') || ($currentRoute === 'distributor.create') || ($currentRoute === 'distributor.edit') ? 'active' : '' }}">
                        <span class="micon bi bi-person-plus"></span>
                        <span class="mtext">Manage Distributor</span>
                    </a>
                </li>
                @endcan

                @can('product-list')
                <li>
                    <a href="{{ route('products.index') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'products.index') || ($currentRoute === 'products.create') || ($currentRoute === 'products.edit') ? 'active' : '' }}">
                        <span class="micon bi bi-bag"></span>
                        <span class="mtext">Manage Product</span>
                    </a>
                </li>
                @endcan

                @can('qrcode-list')
                <li>
                    <a href="{{ route('qrcode.index') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'qrcode.index') || ($currentRoute === 'qrcode.create') ? 'active' : '' }}">
                        <span class="micon bi bi-person"></span>
                        <span class="mtext">Manage QR Code</span>
                    </a>
                </li>
                @endcan

                @can('dispatch-list')
                <li>
                    <a href="{{ route('dispatch.index') }}" class="dropdown-toggle no-arrow {{ ($currentRoute === 'dispatch.index') || ($currentRoute === 'dispatch.create') || ($currentRoute === 'dispatch.edit') ? 'active' : '' }}">
                        <span class="micon bi bi-truck"></span>
                        <span class="mtext">Manage Dispatch</span>
                    </a>
                </li>
                @endcan

                <li class="dropdown @if(in_array($currentRoute, ['dispatch.validation-done-by-distributor-list'])) show @endif">
                    <a href="javascript:;" class="dropdown-toggle">
                        {{-- report Icon --}}
                        <span class="micon bi bi-file-bar-graph"></span>
                        <span class="mtext">Report</span>
                    </a>
                    <ul class="submenu">
                        @can('distributor-report-list')
                        <li>
                            <a href="{{ route('dispatch.validation-done-by-distributor-list') }}" class="{{ ($currentRoute === 'dispatch.validation-done-by-distributor-list') ? 'active' : '' }}">
                                <span class="mtext">Validation Done By Distributor</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="mobile-menu-overlay"></div>
