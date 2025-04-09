import { useState, useContext } from 'react'
import SessionContext from '../components/Session'

export default function Login() {
  const [ email, setEmail ] = useState('')
  const [ password, setPassword ] = useState('')
  const session = useContext(SessionContext)

  //api request to login
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
    <>
      <h1>Login</h1>
      <div className="mb-3 container w-50">
         <label htmlFor="user" className="form-label">Email</label>
         <input className="form-control" type='email' id="user" required value={email} onChange={e => setEmail(e.target.value)} />
          <label htmlFor="password" className="form-label">Password</label>
          <input className="form-control" id="password" type="password" required value={password} onChange={e => setPassword(e.target.value)} />
          <hr/>
          <button
            type="button"
            className="btn btn-primary"
            onClick={() => handleClick()}
          >Login</button>
      </div>
      <a href="#register" className="text-light" onClick={()=>session.set({page: 'register'})}>Register </a>
    </>
  )
}