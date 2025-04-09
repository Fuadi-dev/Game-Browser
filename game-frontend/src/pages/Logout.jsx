import { useEffect, useContext } from 'react'
import SessionContext from '../components/Session'

export default function Logout(props) {
  const session = useContext(SessionContext)

  useEffect(() => {
    // First, logout from the Laravel backend
    if (session.get.token) {
      fetch('http://127.0.0.1:8000/api/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${session.get.token}`
        },
        credentials: 'include'
      })
      .then(response => response.json())
      .then(data => {
        // After backend logout is complete, clear frontend session
        session.set({ 
          page: 'home', 
          user: null, 
          token: null, 
          data: null, 
          message: { type: 'success', text: 'Logged out successfully' }
        });
        
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('user');
        
        // Redirect to login page if backend specifies, otherwise to home
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          window.location.hash = '#home';
        }
      })
      .catch(error => {
        console.error('Error logging out from server:', error);
        // Still clear local session even if server logout fails
        session.set({ 
          page: 'home', 
          user: null, 
          token: null, 
          data: null, 
          message: { type: 'warning', text: 'Logged out with errors' }
        });
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('user');
        window.location.hash = '#home';
      });
    } else {
      // If no token exists, just handle local logout
      setTimeout(() => {
        session.set({ 
          page: 'home', 
          user: null, 
          token: null, 
          data: null, 
          message: { type: 'success', text: 'Logged out successfully' }
        });
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('user');
        window.location.hash = '#home';
      }, 1000);
    }
    
    // No need for cleanup with this approach
  }, []);

  return (
    <>{props.message || 'Logging out...'}</>
  )
}