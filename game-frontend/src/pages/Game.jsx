import { useEffect, useState, useContext } from "react"
import SessionContext from '../components/Session'

export default function Game(props) {
  const [game, setGame] = useState(null)
  const session = useContext(SessionContext)

  useEffect(()=> {
    const getGame = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/game/${props.id}`)
        const data = await response.json()
        if(data.status == 'success'){
          setGame(data.game)
        }else{
          setGame(null)
        }

      }catch (error) {
        console.error("Error fetching data:", error);
      }
    }
    getGame()
  }, [])

  const handlePlay = async (e) => {
    e.preventDefault()
    
    // Periksa apakah pengguna sudah login
    if (session.user && session.user.token) {
      try {
        // Panggil endpoint play untuk menambah hitungan (jika belum pernah memainkan)
        await fetch(`http://127.0.0.1:8000/api/game/${props.id}/play`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${session.user.token}`
          },
        })
      } catch (error) {
        console.error("Error mencatat permainan game:", error)
      }
      
      // Navigasi ke halaman play
      session.set({page: 'play', data: game.id})
    } else {
      // Jika belum login, arahkan ke halaman login
      session.set({page: 'login'})
    }
  }

  return game == null ? <>Loading...</> : (
    <>
      <h1>{game.name}</h1>
      <div className="position-relative">
        <img className="img-fluid mx-auto" style={{ maxWidth : 300 }} src={ game.imgUrl} alt={game.name} />
        <a href={ `#play/${props.id}` } className="position-absolute top-50 start-50 translate-middle" onClick={handlePlay}>play</a>
      </div>
      <p>description: {game.description}</p>
      <p>game version: {game.game_version}</p>
      <p>developer name: {game.developer_name}</p>
    </>
  )
}