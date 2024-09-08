<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">
            <strong>MENU</strong>
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="active">
                        <a class="nav-link" href="/">
                            <i class="bx bx-home-alt" aria-hidden="true"></i>
                            <span>Home</span>
                        </a>    
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-user" aria-hidden="true"></i>
                            <span>Users</span>
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-roles')}}">
                                    Roles
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"  >
                                    Designation
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-users')}}">
                                    User Accounts
                                </a>
                            </li>			
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-cylinder" aria-hidden="true"></i>
                            <span>Items</span>
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-item-groups')}}">
                                    <span>Item Groups</span>
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-items')}}">
                                    Items
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-items-2')}}">
                                    Item Pipes
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-edit-alt" aria-hidden="true"></i>
                            <span>Accounts</span>                
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{route('all-acc')}}">
                                    Chart Of Accounts
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-acc-groups')}}">
                                    COA Groups
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-acc-sub-heads-groups')}}">
                                    COA Sub Heads
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-copy-alt" aria-hidden="true"></i>
                            <span>Vouchers</span>     
                            
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-jv1')}}">
                                    Journal Voucher 1
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-jv2')}}">
                                    Journal Voucher 2
                                </a>
                            </li>	
                            <li>
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link  " href="#">
                            <i class="bx bx-purchase-tag" aria-hidden="true"></i>
                            <span>Purchase</span>     
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-purchases1')}}">
                                    Purchase 1
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-purchases2')}}" >
                                    Purchase 2
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="/sales/new-invoice">
                                    Purchase Return
                                </a>
                            </li>								
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-basket" aria-hidden="true"></i>
                            <span>Sale</span>   
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-saleinvoices')}}">
                                    Sale 1
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-sale2invoices')}}">
                                    Sale 2
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="#">
                                    Sale Return
                                </a>
                            </li>								
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link  " href="#">
                            <i class="bx bx-bar-chart" aria-hidden="true"></i>
                            <span>Stock Pipe</span>   
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-tstock-in')}}">
                                    Stock In
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-tstock-out')}}">
                                    Stock Out
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-tbad-dabs')}}">
                                    Stock Balance
                                </a>
                            </li>								
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-door-open" aria-hidden="true"></i>
                            <span>Stock Doors</span>   
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-stock-in')}}">
                                    Stock In
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-stock-out')}}">
                                    Stock Out
                                </a>
                            </li>
                            <li>
                                <a class="nav-link"   href="{{ route('all-bad-dabs')}}">
                                    Stock Balance
                                </a>
                            </li>								
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link  " href="#">
                            <i class="bx bx-file" aria-hidden="true"></i>
                            <span>Purchase Orders</span>  
                    
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="{{ route('all-po')}}">
                                    P.O 
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-tpo')}}">
                                    P.O Pipe/Garder
                                </a>
                            </li>							
                        </ul>
                    </li>
                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-right-arrow" aria-hidden="true"></i>
                            <span>Quotations</span>  
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="#">
                                    Quotation 1
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-tquotation')}}">
                                    Quotation 2
                                </a>
                            </li>							
                        </ul>
                        
                    </li>

                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="bx bx-right-arrow" aria-hidden="true"></i>
                            <span>Others</span>  
                        </a>
                        <ul class="nav nav-children">
                            <li>
                                <a class="nav-link"   href="#">
                                    Weight
                                </a>
                            </li>	
                            <li>
                                <a class="nav-link"   href="{{ route('all-complains')}}">
                                    Complains
                                </a>
                            </li>							
                        </ul>
                        
                    </li>
                </ul>	
            </nav>
		</div>

        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>