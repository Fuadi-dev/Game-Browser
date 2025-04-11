import '../App.css'
import { useContext, useState } from 'react'
import SessionContext from './Session'

export default function Header() {
  const session = useContext(SessionContext);
  const [menuOpen, setMenuOpen] = useState(false);
  
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
    setMenuOpen(false);
    if (item.toLowerCase() === 'admin panel') {
      // Use the redirect function we added to the session context
      session.redirectToAdminPanel();
    } else {
      session.set({page: item.toLowerCase(), data: null});
    }
  };
  
  const menu = getMenuItems();
  
  return (
    <header className="header-container py-3 mb-4">
      <div className="container d-flex flex-wrap justify-content-between align-items-center">
        <a href="#home" onClick={() => session.set({page: 'home'})} className="d-flex align-items-center mb-2 mb-lg-0 text-decoration-none">
          <div className="logo-container me-2">
            <i className="bi bi-controller fs-2"></i>
          </div>
          <h3 className="m-0 game-title">GameZone</h3>
        </a>

        {/* Hamburger menu for mobile */}
        <button 
          className="navbar-toggler d-md-none" 
          type="button" 
          onClick={() => setMenuOpen(!menuOpen)}
          aria-label="Toggle navigation"
        >
          <i className={`bi ${menuOpen ? 'bi-x' : 'bi-list'} fs-2`}></i>
        </button>

        <nav className={`nav-menu ${menuOpen ? 'show' : ''} nav-masthead justify-content-center`}>
          {menu.map((item, i) => {
            const active = item.toLowerCase() === session.get.page;
            return (
              <a key={i}
                className={`nav-link fw-bold py-2 px-3 ${active ? 'active' : ''}`}
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
          <div className="user-badge d-none d-md-flex align-items-center">
            <div className="avatar me-2">
              <i className="bi bi-person-circle"></i>
            </div>
            <span className="username">{session.get.user.name}</span>
          </div>
        )}
      </div>
    </header>
  )
}
