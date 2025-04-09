import '../App.css'
import { useContext } from 'react'
import SessionContext from './Session'

export default function Header() {
  const session = useContext(SessionContext);
  
  // Create menu items based on user role
  const getMenuItems = () => {
    const items = ['Home'];
    
    // Add Admin Panel link for admin and developer users
    if (session.get.user && (session.get.user.role === 'admin' || session.get.user.role === 'developer')) {
      items.push('Admin Panel');
    }
    
    // Add authentication links
    if (session.get.user) {
      items.push('Logout');
    } else {
      items.push('Login', 'Register');
    }
    
    return items;
  };
  
  const handleMenuClick = (item) => {
    if (item.toLowerCase() === 'admin panel') {
      // Use the redirect function we added to the session context
      session.redirectToAdminPanel();
    } else {
      session.set({page: item.toLowerCase(), data: null});
    }
  };
  
  const menu = getMenuItems();
  
  return (
    <header className="mb-auto">
      <div>
        <h3 className="float-md-start mb-0">Game Browser</h3>
        <nav className="nav nav-masthead justify-content-center float-md-end">
          {menu.map((item, i) => {
            const active = item.toLowerCase() === session.get.page;
            return (
              <a key={i}
                className={"nav-link fw-bold py-1 px-0" + (active ? ' active' : '')}
                href={'#' + (item === 'Admin Panel' ? '' : item.toLowerCase())}
                onClick={(e) => {
                  if (item === 'Admin Panel') e.preventDefault();
                  handleMenuClick(item);
                }}
              >
                {item}
              </a>
            )
          })}
        </nav>
        {session.get.user && (
          <small className="float-md-end me-3 text-white-50">
            {/* Welcome, {session.get.user.name} */}
          </small>
        )}
      </div>
    </header>
  )
}
