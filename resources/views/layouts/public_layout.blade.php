<!doctype html>
<!--
Author: 249Plus
Product Name: 249Plus POS 
Product Version: 6.0
Purchase: https://codecanyon.net/item/249Plus-pos-multiple-store-point-of-sale-billing-and-inventory-management-application/25742626
Website: https://www.249Plus.com
Contact: 249Plus@gmail.com
License: For each use you must have a valid license purchased only from above link in order to legally use the application.
-->
<html lang="en">
    @include('layouts.header')
    <div id="app">
        <body>
            <div class="wrapper">
                <div class="content">
                    @yield('content')
                </div>
            </div>     
        </body>
    </div>
    @include('layouts.scripts')
    @stack('scripts')
</html>