import HomeComponent from "./components/Home";
import LoginComponent from "./components/Auth/Login";
import RegisterComponent from "./components/Auth/Register";
import LogoutComponent from "./components/Auth/Logout";
import EditComponent from "./components/Auth/Edit";



const routes = [
    {
        path: '/',
        component: HomeComponent,
        name: 'home'
    },
    {
        path: '/login',
        component: LoginComponent,
        name: 'login',
        meta: {
            requiresVisitors: true
        }
    },
    {
        path: '/register',
        component: RegisterComponent,
        name: 'register',
        meta: {
            requiresVisitors: true
        }
    },
    {
        path: '/edit',
        component: EditComponent,
        name: 'edit',
        meta: {
            requiresVisitors: true
        }
    },
    {
        path: '/logout',
        component: LogoutComponent,
        name: 'logout'
    },

];


export default routes
