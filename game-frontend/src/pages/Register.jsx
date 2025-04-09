import { useState, useContext } from 'react'
import SessionContext from '../components/Session'

export default function Register() {
  const [ name, setName ] = useState('')
  const [ email, setEmail ] = useState('')
  const [ password, setPassword ] = useState('')
  const [ confirmPassword, setConfirmPassword ] = useState('')
  const session = useContext(SessionContext)

  //api request to Register
  const Register = async () => {
    try{
      const response = await fetch(`http://127.0.0.1:8000/api/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name,email, password, confirm_password : confirmPassword })
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
    <>
      <h1>Register</h1>
      <div className="mb-3 container w-50">
         <label htmlFor="user" className="form-label">Username</label>
         <input className="form-control" type='text' id="name" required value={name} onChange={e => setName(e.target.value)} />
         <label htmlFor="user" className="form-label">Email</label>
         <input className="form-control" type='email' id="email" required value={email} onChange={e => setEmail(e.target.value)} />
          <label htmlFor="password" className="form-label">Password</label>
          <input className="form-control" id="password" type="password" required value={password} onChange={e => setPassword(e.target.value)} />
          <label htmlFor="password" className="form-label">Confirm Password</label>
          <input className="form-control" id="confirmPassword" type="password" required value={confirmPassword} onChange={e => setConfirmPassword(e.target.value)} />
          <hr/>
          <button
            type="button"
            className="btn btn-primary"
            onClick={() => handleClick()}
          >Register</button>
      </div>
    <a href="#login" className="text-light" onClick={()=>session.set({page: 'login'})}>Login </a>
    </>
  )
}