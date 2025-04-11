import { useState, useContext } from 'react'
import SessionContext from '../components/Session'

export default function Register() {
  const [ name, setName ] = useState('')
  const [ email, setEmail ] = useState('')
  const [ password, setPassword ] = useState('')
  const [ confirmPassword, setConfirmPassword ] = useState('')
  const session = useContext(SessionContext)

  //api request to Register - tetap sama untuk mempertahankan fungsi backend
  const Register = async () => {
    try{
      const response = await fetch(`http://127.0.0.1:8000/api/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name, email, password, confirm_password : confirmPassword })
      })
      const data = await response.json()
      return data
    }catch(error){
      return {
        status: 500,
        message: 'Internal Server Error'
      }
    }
  }

  const handleClick = async () => {
    const data = await Register()
    if(data.status == 'success'){
      session.set({page : 'home', user : data.user, token : data.token, message : {type : 'success', text: data.message}})
      sessionStorage.setItem('token', data.token)
      sessionStorage.setItem('user', JSON.stringify(data.user))
    }else{
      session.set({page : 'register', message : {type : 'danger', text: data.message}})
    }
  }

  return (
    <div className="auth-container">
      <div className="auth-card">
        <div className="auth-header">
          <h2 className="auth-title">Daftar Akun</h2>
          <p className="auth-subtitle">Buat akun baru untuk akses penuh ke semua game</p>
        </div>
        
        <div className="auth-body">
          <div className="form-floating mb-3">
            <input 
              type="text" 
              className="form-control custom-input" 
              id="name" 
              placeholder="Nama Pengguna"
              value={name} 
              onChange={e => setName(e.target.value)}
            />
            <label htmlFor="name">Nama Pengguna</label>
          </div>
          
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
          
          <div className="form-floating mb-3">
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
          
          <div className="form-floating mb-4">
            <input 
              type="password" 
              className="form-control custom-input" 
              id="confirmPassword" 
              placeholder="Konfirmasi Password"
              value={confirmPassword} 
              onChange={e => setConfirmPassword(e.target.value)}
            />
            <label htmlFor="confirmPassword">Konfirmasi Password</label>
          </div>
          
          <button
            type="button"
            className="btn btn-primary btn-glow w-100 py-2 mb-3"
            onClick={() => handleClick()}
          >
            <i className="bi bi-person-plus me-2"></i>
            Daftar
          </button>
          
          <div className="text-center">
            <p className="mb-0">Sudah punya akun? 
              <a href="#login" className="ms-1 auth-link" onClick={()=>session.set({page: 'login'})}>
                Masuk
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  )
}