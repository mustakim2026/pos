<template>
    <div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <router-link class="nav-link active" aria-current="page" :to="{ name: 'UserList' }">Active</router-link>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-expanded="false">Setting</a>
                <ul class="dropdown-menu">
                    <li><router-link class="dropdown-item" :to="{ name: 'observe' }">observe</router-link></li>
                    <li><router-link class="dropdown-item" :to="{ name: 'unit' }">Unit</router-link></li>
                    <li><router-link class="dropdown-item" :to="{ name: 'smv_library' }">SMV Library</router-link></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-expanded="false">Report</a>
                <ul class="dropdown-menu">
                    <li><router-link class="dropdown-item" :to="{ name: 'report' }">Efficency Report</router-link></li>

                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>
        </ul>

    </div>
</template>