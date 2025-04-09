import { useEffect, useState, useContext } from "react"
import SessionContext from '../components/Session'

export default function Game(props) {
  const [game, setGame] = useState(null)
  const [recommendations, setRecommendations] = useState([])
  const session = useContext(SessionContext)

  useEffect(()=> {
    const getGame = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/game/${props.id}`)
        const data = await response.json()
        if(data.status == 'success'){
          setGame(data.game)
          
          // Fetch recommendations after getting game details
          fetchRecommendations(props.id)
        }else{
          setGame(null)
        }

      }catch (error) {
        console.error("Error fetching data:", error);
      }
    }
    getGame()
  }, [props.id])
  
  const fetchRecommendations = async (gameId) => {
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/game/${gameId}/recommendations`)
      const data = await response.json()
      if(data.status == 'success'){
        setRecommendations(data.recommendations)
      }
    } catch (error) {
      console.error("Error fetching recommendations:", error)
    }
  }

  const handlePlay = async (e) => {
    e.preventDefault()
    
    // Periksa apakah pengguna sudah login
    if (session.get.token) {
      console.log("Pengguna sudah login");
      try {
        // Panggil endpoint play untuk menambah hitungan (jika belum pernah memainkan)
        await fetch(`http://127.0.0.1:8000/api/game/${props.id}/play`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${session.get.token}`
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
        <a href={ `#play/${props.id}` } className="position-absolute top-50 start-50 translate-middle display-3" onClick={handlePlay}><i className="bi bi-play-fill"></i></a>
      </div>
      <p>description: {game.description}</p>
      <p>game version: {game.game_version}</p>
      <p>developer name: {game.developer_name}</p>
      
      {/* Game Recommendations Section */}
      {recommendations.length > 0 && (
        <div className="mt-5">
          <h3 className="mb-3">Recommended Games</h3>
          <div className="row">
            {recommendations.map(game => (
              <div className="col-2" key={game.id} style={{ margin: '10px' }}>
                <a 
                  href={`#game/${game.id}`}
                  onClick={(e) => {
                    e.preventDefault();
                    session.set({page: 'game', data: game.id});
                  }}
                >
                  <figure className="figure">
                    <img
                      src={game.imgUrl}
                      width="100"
                      height="100"
                      className="figure-img rounded-circle bg-light"
                      alt={game.name}
                    />
                    <figcaption className="figure-caption text-light">
                      {game.name}
                    </figcaption>
                  </figure>
                </a>
              </div>
            ))}
          </div>
        </div>
      )}
    </>
  )
}