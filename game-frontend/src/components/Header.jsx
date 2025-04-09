import '../App.css'
import { useContext } from 'react'
import SessionContext from './Session'

export default function Header() {
const session = useContext(SessionContext);
 const menu = [
    'Home',
    'Games',
    session.get.user ? 'Logout' : 'Login',
    session.get.user ? '' : 'Register',
  ]
  return (
    <header className="mb-auto">
    <div>
      <h3 className="float-md-start mb-0">Cover</h3>
      <nav className="nav nav-masthead justify-content-center float-md-end">
        {menu.map((item, i) => {
            const active = item.toLowerCase() == session.get.page;
            return(
            <a key={i}
            className={"nav-link fw-bold py-1 px-0" + (active ? ' active' : '')}
            href={'#' + item.toLowerCase()}
            onClick = {() => session.set({page: item.toLowerCase(), data: null})}
            >
            {item}
            </a>
            )
        })}
      </nav>
      {/* <p>
        {session.get.user?.name}
      </p> */}
    </div>
  </header>
  )
}
