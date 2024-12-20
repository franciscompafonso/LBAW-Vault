<script type="text/javascript" src="{{ url('js/partials/topbar.js') }}" defer></script>

<!-- Skip to Content Link -->
<a href="#main" 
   class="absolute left-[-999px] top-[-999px] focus:left-0 focus:top-0 focus:z-50 focus:bg-ultraViolet focus:text-white focus:px-4 focus:py-2 focus:rounded-md">
   Skip to Content
</a>

<!-- Header Section -->
<header class="relative flex flex-wrap items-center justify-between p-6">
    <!-- Logo -->
    <h1 class="font-bold">
        <a href="{{ url(path: '/home') }}" 
           class="text-5xl font-montserrat hover:text-darkSlateGrey 
                  {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" aria-label="Lend-A-Hand Home Page">
            Lend-A-Hand
        </a>
    </h1>

    @include('partials.search_bar')
    
    <!-- Navigation Icons -->
    <div class="flex items-center space-x-6">
        @if (Auth::guard('web')->check() )
            @can('sendUnblockRequest', auth()->user())
                <a href="{{ url('/unblock-request') }}" 
                title="Unblock Request" 
                aria-label="Request Unblock"
                class="{{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} hover:text-darkSlateGrey">
                    <i class="text-4xl fa-solid fa-lock-open"></i>
                </a>
            @endcan
            @can('create', auth()->user())
                <a href="{{ url('/event/create') }}" 
                title="Create Event" 
                aria-label="Create Event"
                class="{{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} hover:text-darkSlateGrey">
                    <i class="text-4xl fa-solid fa-plus"></i>
                </a>
            @endcan
            


            <div class="relative">
                <!-- Bell Icon with Notification Count -->
                <a id="notifications-button" onclick="toggleNotificationsDropdown()" class="relative cursor-pointer">
                    <i class="text-4xl hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }} fa-solid fa-bell"></i>
                    
                    <!-- Notification Count Circle -->
                    <div id="noti-indicator" class="absolute top-0 right-0 flex items-center justify-center hidden w-5 h-5 font-bold text-white translate-x-2 translate-y-1 bg-red-500 rounded-full text-[10px]">
                    </div>
                </a>

                <!-- Notifications Dropdown -->
                <div id="notifications-dropdown" class="absolute right-0 z-10 font-montserrat hidden p-6 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-[32rem] max-h-[750px] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <p id="no-notifications" class="text-xl text-center text-gray-500">There are no notifications.</p>
                        <!-- Dismiss All Button -->
                        <button id="dismiss-all-btn" onclick="dismissAllNotifications()" class="hidden font-bold border-transparent font-montserrat focus:text-ultraViolet focus:border-transparent focus:bg-white hover:border-transparent text-ultraViolet hover:bg-white hover:text-wisteria" aria-label="Dismiss all notifications">
                            Dismiss all
                        </button>
                    </div>
                    <div id="notifications-list"></div>
                </div>
                
            </div>
            
            <!-- Profile Icon -->
            <div class="relative">
                <a id="profile-button" onclick="toggleProfileDropdown()" class="cursor-pointer">
                    <img src="{{ asset('images/profile-pictures/' . (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                        alt="{{ Auth::user()->name ? Auth::user()->name . ' profile picture' : 'Default profile picture' }}" 
                        class="object-cover w-16 h-16 border-2 rounded-full hover:border-darkSlateGrey 
                        {{ Auth::guard('admin')->check() ? 'border-ultraViolet' : 'border-wisteria' }}">
                </a>

                <!-- Profile Dropdown -->
                <div id="profile-dropdown" 
                    class="absolute right-0 z-10 hidden mt-2 bg-white border border-gray-300 rounded-lg shadow-lg w-auto max-w-[90vw]" aria-label="User Profile">
                    <!-- Profile Info Card -->
                    <a href="{{ route('user.profile', ['username' => Auth::user()->username]) }}" 
                    class="block p-6 hover:bg-gray-100" aria-label="View Profile">
                        <div class="flex items-center gap-6">
                            <!-- Profile Image -->
                            <img src="{{ asset('images/profile-pictures/' . (Auth::user()->picture->path ?? App\Models\User::DEFAULT_PIC)) }}" 
                                alt="{{ Auth::user()->name ? Auth::user()->name . ' profile picture' : 'Default profile picture' }}" 
                                class="object-cover w-16 h-16 border-2 rounded-full 
                                        {{ Auth::guard('admin')->check() ? 'border-ultraViolet' : 'border-wisteria' }}">
                            
                            <!-- User Info -->
                            <div class="min-w-0">
                                <p class="mr-16 font-semibold text-gray-800 font-montserrat whitespace-nowrap text-dynamic-xl">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="mr-16 text-gray-500 font-montserrat whitespace-nowrap text-dynamic-lg">
                                    {{ Auth::user()->username }}
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Logout Option -->
                    <a href="{{ url('/logout') }}" 
                    class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100" aria-label="Sign out">
                        <i class="text-xl fa-solid fa-right-from-bracket"></i>
                        <span class="font-medium font-montserrat">Sign out</span>
                    </a>
                </div>
            </div>
        @endif

        @if (Auth::guard('admin')->check())
        
            <a href="{{ route('admin.users.create') }}" 
               class="hover:text-darkSlateGrey text-ultraViolet" 
               title="Create User"
               aria-label="Create User">
                <i class="text-4xl fa-solid fa-user-plus"></i>
            </a>
            <a href="{{ url('/admin/user-management') }}" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
               title="Users"
               aria-label="User Management">
                <i class="text-4xl fa-solid fa-users-gear"></i>
            </a>
            <a href="{{ url('/admin/create') }}" 
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
               title="Admins"
               aria-label="Create Admin">
                <i class="text-4xl fa-solid fa-user-tie"></i>
            </a>
            <a href="{{ url('/admin/unblock-requests') }}" 
                class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}" 
                title="Unblock Requests"
                aria-label="Unblock Requests">
                    <i class="text-4xl fa-solid fa-lock"></i>
            </a>
            <a href="{{ url('/logout') }}" 
               title="Logout" aria-label="Logout"
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
                <i class="text-4xl fa-solid fa-right-from-bracket"></i>
            </a>
        @endif

        @if (Auth::guest() && !Request::is('login') && (!Auth::guard('admin')->check()))
            <!-- Login Icon -->
            <a href="{{ url('/login') }}" 
               title="Login" aria-label="Login"
               class="hover:text-darkSlateGrey {{ Auth::guard('admin')->check() ? 'text-ultraViolet' : 'text-wisteria' }}">
                <i class="text-4xl fa-solid fa-right-to-bracket"></i>
            </a>
        @endif
    </div>
</header>