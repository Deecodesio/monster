<template>
    <div class="navbar-container d-flex content align-items-center">
        <!-- Nav Menu Toggler -->
        <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item">
                <b-link class="nav-link" @click="toggleVerticalMenuActive">
                    <feather-icon icon="MenuIcon" size="21" />
                </b-link>
            </li>
        </ul>

            <!-- Left: Bookmarks + Status -->
            <div class="d-none d-lg-flex align-items-center">
                <div class="bookmark-wrapper d-flex align-items-center">
                    <bookmarks />
                    <b-navbar-nav>
                        <Status />
                    </b-navbar-nav>
                </div>
            </div>

            <!-- Center: Username -->
            <div class="d-flex justify-content-center flex-grow-1">
                <p class="user-name font-weight-bolder mb-0" style="color: #ff006b; font-weight: 600; font-size: 25px;">
                    {{ store_userData.fullName || store_userData.username }}
                </p>
            </div>

            <!-- Right: Locale, Toggler, Dropdowns -->
            <b-navbar-nav class="d-flex align-items-center ml-auto">
                <locale />
                <dark-toggler class="d-none d-lg-block" />
                <search-bar />
                <cart-dropdown />
                <notification-dropdown />
                <user-dropdown />
            </b-navbar-nav>

        <!-- Left Col -->
        <!-- <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex"> -->

        <!-- Bookmarks Container -->
        <!-- <bookmarks />
      <b-navbar-nav >
        <Status />
      </b-navbar-nav>
       -->
        <!-- </div> -->

        <!-- <b-navbar-nav class="nav align-items-center ml-auto"> -->
        <!-- <locale />
      <dark-Toggler class="d-none d-lg-block" />
      <search-bar />
      <cart-dropdown />
      <notification-dropdown />
      <user-dropdown />
    </b-navbar-nav> -->
    </div>
</template>

<script>
import { BLink, BNavbarNav } from "bootstrap-vue";
import Bookmarks from "./components/Bookmarks.vue";
import Locale from "./components/Locale.vue";
import SearchBar from "./components/SearchBar.vue";
import DarkToggler from "./components/DarkToggler.vue";
import CartDropdown from "./components/CartDropdown.vue";
import NotificationDropdown from "./components/NotificationDropdown.vue";
import UserDropdown from "./components/UserDropdown.vue";
import Status from "./components/Status.vue";

export default {
    components: {
        BLink,

        // Navbar Components
        BNavbarNav,
        Bookmarks,
        Locale,
        SearchBar,
        DarkToggler,
        CartDropdown,
        NotificationDropdown,
        UserDropdown,
        Status,
    },
    data() {
        return {
            store_userData: JSON.parse(localStorage.getItem("store_userData"))
        };
    },
    props: {
        toggleVerticalMenuActive: {
            type: Function,
            default: () => {},
        },
    },
};
</script>
