import Error from "./pages/Error"
import Home from "./pages/Home"
import Game from "./pages/Game"
import Games from "./pages/Games"
import Login from "./pages/Login"
import Register from "./pages/Register"
import Logout from "./pages/Logout"
import Play from "./pages/Play"
import Dashboard from "./dashboard/pages/Dashboard"
import Users from "./dashboard/pages/Users"


const routes = {
    'error' : status => <Error status={status}/>,
    'home' : _ => <Home/>,
    'games' : data => <Games sheet={data}/>,
    'game' : data => <Game id={data}/>,
    'play' : data => <Play id={data}/>,
    'login' : _ => <Login/>,
    'register' : _ => <Register/>,
    'logout' : message => <Logout message={message}/>,
    'dashboard' : _ => <Dashboard/>,
    'users' : _ => <Users/>,
}
export default routes;