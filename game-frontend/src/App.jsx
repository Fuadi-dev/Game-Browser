import { useState, useEffect } from 'react'
import Header from './components/Header'
import Body from './components/Body'
import Footer from './components/Footer'
import SessionContext from './components/Session'
import Logout from './pages/Logout'
import Alert from './components/Alert'
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'

export default function App() {

  // page state for the page
  const [getSession, setSession] = useState({page : 'home'})
  const [adminRedirectComplete, setAdminRedirectComplete] = useState(false)

  const session = {
    get: getSession,
    set: data => setSession(
      sess => ({...sess, ...data, oldPage: getSession.page, oldData: getSession.data})
    )
  }

  useEffect(() => {
    const token = sessionStorage.getItem('token') || null
    const user = sessionStorage.getItem('user') || null
    const {hash} = window.location
    const [page, data] = hash.replace('#', '').split('/')
    session.set({page : page || 'home', data, token, user: JSON.parse(user)})
  }, [])

  useEffect(()=>{
    const sessionTimeout = 360000; // 1 hour in milliseconds
    let timeoutId;
    const resetTimer = () => {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        if(!sessionStorage.getItem('token')){
          return;
        }
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('user');

        session.set({page: 'logout', data : 'session expired'});
      }, sessionTimeout);
    };

    //Reset timer user activity
    const events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart', 'keypress'];

    //start timer
    if (session.get.token) {
      resetTimer();

      //add event listener
      events.forEach((event) => {
        document.addEventListener(event, resetTimer);
      });
    }

    return () => {
      //remove event listener
      events.forEach((event) => {
        document.removeEventListener(event, resetTimer);
      });
    };
  }, [session.get.token]);

  useEffect(() => {
    const toast = document.querySelector('.toast');
    if(!toast) return;
    new bootstrap.Toast(toast).show();
  },[session.get.message])

  // Function to handle admin redirect when user clicks the menu item
  const redirectToAdminPanel = () => {
    if (session.get.token && session.get.user && 
        (session.get.user.role === 'admin' || session.get.user.role === 'developer')) {
      
      fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
        method: "GET",
        credentials: "include",
      })
      .then(response => {
        const xsrfCookie = document.cookie
          .split('; ')
          .find(row => row.startsWith('XSRF-TOKEN='));
        
        let csrfToken = null;
        if (xsrfCookie) {
          const tokenValue = xsrfCookie.split('=')[1];
          try {
            csrfToken = decodeURIComponent(tokenValue);
          } catch (e) {
            console.error('Failed to decode CSRF token:', e);
          }
        }
        
        return fetch('http://127.0.0.1:8000/api/admin/auth', {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "Authorization": `Bearer ${session.get.token}`,
            "X-CSRF-TOKEN": csrfToken || '', 
            "X-XSRF-CSRF-TOKEN": csrfToken || '' 
          },
          credentials: "include",
        });
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          window.location.href = 'http://127.0.0.1:8000/login';
        }
      })
      .catch((error) => {
        console.error('Error authenticating with Laravel:', error);
        session.set({ 
          message: {
            type: 'danger',
            text: 'Terjadi kesalahan saat mengakses admin panel.'
          }
        });
      });
    }
  };

  // Add the redirect function to the session context
  session.redirectToAdminPanel = redirectToAdminPanel;

  return (
    <SessionContext.Provider value={session}>
      <>
        <div className="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column text-bg-dark">
          <Header/>
          <Body/>
          <Footer/>
        </div>
      
        {session.get.message && <Alert type={session.get.message.type} message={session.get.message.text}/>}
      </>
    </SessionContext.Provider>
  )
}
