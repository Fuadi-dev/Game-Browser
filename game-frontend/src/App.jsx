import { useState, useEffect } from 'react'
import Header from './components/Header'
import Body from './components/Body'
import Footer from './components/Footer'
import SessionContext from './components/Session'
import Logout from './pages/Logout'
import Alert from './components/Alert'
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'
import Nav from './dashboard/components/Nav'
import Sidebar from './dashboard/components/Sidebar'


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
        // console.log(session.get.token);
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
    // session.set({message: null})
  },[session.get.message])

  useEffect(() => {
    // Jika user sudah login dan bukan player dan belum diarahkan ke admin panel
    if(session.get.token && session.get.user && session.get.user.role !== 'player' && !adminRedirectComplete){
      // PERTAMA - dapatkan CSRF cookie
      fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
        method: "GET",
        credentials: "include",
      })
      .then(response => {
        // Log semua cookie untuk debugging
        console.log("All cookies:", document.cookie);
        
        // Perbaikan ekstraksi CSRF token
        const xsrfCookie = document.cookie
          .split('; ')
          .find(row => row.startsWith('XSRF-TOKEN='));
        
        console.log("XSRF cookie found:", xsrfCookie);
        
        let csrfToken = null;
        if (xsrfCookie) {
          const tokenValue = xsrfCookie.split('=')[1];
          try {
            csrfToken = decodeURIComponent(tokenValue);
            console.log("Decoded token:", csrfToken);
          } catch (e) {
            console.error('Failed to decode CSRF token:', e);
          }
        }
        
        console.log('Final CSRF Token:', csrfToken);
        
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
          // Tandai bahwa redirect sudah dilakukan
          setAdminRedirectComplete(true);
          
          // Simpan data user untuk kembali nanti
          const userRole = session.get.user.role;
          const userName = session.get.user.name;
          
          // Hapus sesi dari sessionStorage
          sessionStorage.removeItem('token');
          sessionStorage.removeItem('user');
          
          // Hapus sesi dari state aplikasi
          session.set({
            token: null,
            user: null,
            message: {
              type: 'info',
              text: `Selamat datang di admin panel, ${userName}. Sesi game telah dihapus.`
            }
          });
          
          // Arahkan ke admin panel
          window.location.href = data.redirect;
        } else if(data.status === "error" && data.redirect){
          // Tandai bahwa redirect sudah dilakukan
          setAdminRedirectComplete(true);
          
          // Hapus sesi
          sessionStorage.removeItem('token');
          sessionStorage.removeItem("user");
          session.set({
            token: null, 
            user: null,
            message: {
              type: 'warning',
              text: 'Tidak dapat masuk ke admin panel. Sesi telah dihapus.'
            }
          });
          
          if(data.redirect){
            window.location.href = data.redirect;
          }
        }
      })
      .catch((error) => {
        console.error('Error authenticating with laravel:', error);
        
        // Tandai bahwa redirect sudah dilakukan (meskipun gagal)
        setAdminRedirectComplete(true);
        
        // Hapus sesi
        sessionStorage.removeItem("token");
        sessionStorage.removeItem("user");
        session.set({ 
          token: null, 
          user: null,
          message: {
            type: 'danger',
            text: 'Terjadi kesalahan saat mengakses admin panel. Sesi telah dihapus.'
          }
        });
      });
    }
  },[session.get.token, session.get.user, adminRedirectComplete]);


  return (
    <SessionContext.Provider value={session}>
      <>
        {!session.get.token || !session.get.user || session.get.user.role === 'player' ? (
          <div className="container-fluid d-flex w-100 h-100 p-3 mx-auto flex-column text-bg-dark">
            <Header/>
            <Body/>
            <Footer/>
          </div>
        ) : (
          <div
            className="d-flex justify-content-center align-items-center"
            style={{ height: "100vh", width: "100vw" }}
          >
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Redirecting to admin panel...</span>
            </div>
            <p className="ms-2">Redirecting to admin dashboard...</p>
          </div>
        )}
      
        {session.get.message && <Alert type={session.get.message.type} message={session.get.message.text}/>}
      </>
    </SessionContext.Provider>
  )
}
