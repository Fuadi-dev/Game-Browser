import Error from "./pages/Error"
import Game from "./pages/Game"
import Games from "./pages/Games"
import Login from "./pages/Login"
import Register from "./pages/Register"
import Logout from "./pages/Logout"
import Play from "./pages/Play"


const routes = {
    'error' : status => <Error status={status}/>,
    'home' : data => <Games sheet={data}/>,
    'game' : data => <Game id={data}/>,
    'play' : data => <Play id={data}/>,
    'login' : _ => <Login/>,
    'register' : _ => <Register/>,
    'logout' : message => <Logout message={message}/>,
}
export default routes;