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
                    <a class="nav-link" target="_blank" href="{{ route('all-roles')}}">
                        Roles
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank">
                        Designation
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-users')}}">
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
                    <a class="nav-link" target="_blank" href="{{ route('all-item-groups')}}">
                        <span>Item Groups</span>
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-items')}}">
                        Items
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-items-2')}}">
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
                    <a class="nav-link" target="_blank" href="{{route('all-acc')}}">
                        Chart Of Accounts
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-acc-groups')}}">
                        COA Groups
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-acc-sub-heads-groups')}}">
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
                    <a class="nav-link" target="_blank" href="{{ route('all-jv1')}}">
                        Journal Voucher 1
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-jv2')}}">
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
                    <a class="nav-link" target="_blank" href="{{ route('all-purchases1')}}">
                        Purchase 1
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-purchases2')}}" >
                        Purchase 2
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="/sales/new-invoice">
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
                    <a class="nav-link" target="_blank" href="{{ route('all-saleinvoices')}}">
                        Sale 1
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-sale2invoices')}}">
                        Sale 2
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="#">
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
                    <a class="nav-link" target="_blank" href="{{ route('all-tstock-in')}}">
                        Stock In
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-tstock-out')}}">
                        Stock Out
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-tbad-dabs')}}">
                        Stock Balance
                    </a>
                </li>								
            </ul>
        </li>
        <li class="nav-parent">
            <a class="nav-link" href="#">
                <i class="bx bx-file" aria-hidden="true"></i>
                <span>Stock Doors</span>   
            </a>
            <ul class="nav nav-children">
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-stock-in')}}">
                        Stock In
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-stock-out')}}">
                        Stock Out
                    </a>
                </li>
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-bad-dabs')}}">
                        Stock Balance
                    </a>
                </li>								
            </ul>
        </li>
        <li class="nav-parent">
            <a class="nav-link  " href="#">
                <i class="bx bx-file" aria-hidden="true"></i>
                <span>Reports</span>  
          
            </a>
            <ul class="nav nav-children">
                <li>
                    <a class="nav-link" target="_blank">
                        Main Option
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank">
                        Main Option
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
                    <a class="nav-link" target="_blank" href="{{ route('all-quotation')}}">
                        Quotation
                    </a>
                </li>	
                <li>
                    <a class="nav-link" target="_blank" href="{{ route('all-complains')}}">
                        Complains
                    </a>
                </li>							
            </ul>
        </li>
    </ul>	
</nav>