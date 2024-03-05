<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @include('admin.master.inc.header-style')
    </head>
    <body class="app">
        <!-- Page Loader Start -->
        <div id="loader"><div class="spinner"></div></div>
        <script type="text/javascript">
            window.addEventListener("load", () => {
                const loader = document.getElementById("loader");
                setTimeout(() => {
                    loader.classList.add("fadeOut");
                }, 100);
            });
        </script>
        <!-- Page Loader End -->
        <div>
            <!-- Left Sidebar Start -->
            @include('admin.master.inc.sidebar')
            <!-- Left Sidebar End -->
            <div class="page-container">
                <!-- Header Top Navigation Start -->
                @include('admin.master.inc.header-top-navigation')
                <!-- Header Top Navigation End -->

                <!-- Main Body Part Start -->
                <main class="main-content bgc-grey-100">
                    <div id="mainContent">
                        @yield('content')
                    </div>
                </main>
                <!-- Main Body Part End -->

                <!-- Footer Start -->
                @include('admin.master.inc.footer')
                <!-- Footer End -->
            </div>
        </div>
        @include('admin.master.inc.footer-script')
    </body>
</html>