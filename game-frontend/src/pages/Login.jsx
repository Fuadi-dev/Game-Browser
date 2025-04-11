import { useState, useContext } from 'react'
import SessionContext from '../components/Session'

export default function Login() {
  const [ email, setEmail ] = useState('')
  const [ password, setPassword ] = useState('')
  const session = useContext(SessionContext)

  //api request to login - tetap sama untuk mempertahankan fungsi backend
  const login = async () => {
    try {
      // Dapatkan CSRF cookie terlebih dahulu
      await fetch(`http://127.0.0.1:8000/sanctum/csrf-cookie`, {
        method: 'GET',
        credentials: 'include',
      });
      
      // Ekstrak token dari cookie
      const token = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];
        
      let csrfToken = '';
      if (token) {
        try {
          csrfToken = decodeURIComponent(token);
        } catch (e) {
          console.error('Failed to decode CSRF token:', e);
        }
      }
      
      // Gunakan token dalam permintaan login
      const response = await fetch(`http://127.0.0.1:8000/api/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'include',
        body: JSON.stringify({ email, password })
      });
      
      const data = await response.json();
      return data;
    } catch(error) {
      console.error('Login error:', error);
      return {
        status: 'error',
        message: 'Internal Server Error'
      };
    }
  }

  const handleClick = async () => {
    const data = await login()
    if(data.status == 'success'){
      session.set({user : data.user, token : data.token, message : {type : 'success', text: data.message}})
      sessionStorage.setItem('token', data.token)
      sessionStorage.setItem('user', JSON.stringify(data.user))
      if (window.location.hash == '#login'){
        const dest = data.user.role == 'player' ? 'home' : 'home'
        window.location.hash = '#' + dest
        session.set({page : dest})
      }else{
        window.location.reload()
      }

    }else{
      session.set({page : 'login', message : {type : 'danger', text: data.message}})
    }
  }

  return (
    <div className="auth-container">
      <div className="auth-card">
        <div className="auth-header">
          <h2 className="auth-title">Masuk</h2>
          <p className="auth-subtitle">Masuk untuk memainkan koleksi game menarik</p>
        </div>
        
        <div className="auth-body">
          <div className="form-floating mb-3">
            <input 
              type="email" 
              className="form-control custom-input" 
              id="email" 
              placeholder="nama@contoh.com"
              value={email} 
              onChange={e => setEmail(e.target.value)}
            />
            <label htmlFor="email">Email</label>
          </div>
          
          <div className="form-floating mb-4">
            <input 
              type="password" 
              className="form-control custom-input" 
              id="password" 
              placeholder="Password"
              value={password} 
              onChange={e => setPassword(e.target.value)}
            />
            <label htmlFor="password">Password</label>
          </div>
          
          <button
            type="button"
            className="btn btn-primary btn-glow w-100 py-2 mb-3"
            onClick={() => handleClick()}
          >
            <i className="bi bi-box-arrow-in-right me-2"></i>
            Masuk
          </button>
          
          <div className="text-center">
            <p className="mb-0">Belum punya akun? 
              <a href="#register" className="ms-1 auth-link" onClick={()=>session.set({page: 'register'})}>
                Daftar sekarang
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  )
}