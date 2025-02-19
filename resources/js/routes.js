import HomeComponent from "./components/Home";
import LoginComponent from "./components/Auth/Login";
import RegisterComponent from "./components/Auth/Register";
import LogoutComponent from "./components/Auth/Logout";
import EditComponent from "./components/Auth/Edit";
import PasswordComponent from "./components/Auth/Password";
import MovementList from "./components/Movements/MovementList";
import UsersListComponent from "./components/Users/UsersList";
import MovementAddComponent from "./components/Movements/MovementAdd";
import UserAddComponent from "./components/Users/UserAdd";
import IncomeAddComponent from "./components/Movements/IncomeAdd";



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
    },
    {
        path: '/password',
        component: PasswordComponent,
        name: 'password',
    },
    {
        path: '/movements',
        component: MovementList,
        name: 'movements',
    },
    {
        path: '/logout',
        component: LogoutComponent,
        name: 'logout'
    },
    {
        path: '/users',
        component: UsersListComponent,
        name: 'users'
    },
    {
        path: '/movementAdd',
        component: MovementAddComponent,
        name: 'movementAdd'
    },
    {
        path: '/useradd',
        component: UserAddComponent,
        name: 'userAdd'
    },
    {
        path: '/incomeAdd',
        component: IncomeAddComponent,
        name: 'incomeAdd'
    }
];


export default routes
