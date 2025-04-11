import { useEffect, useState, useContext } from "react"
import SessionContext from '../components/Session'

export default function Play(props) {
  const [game, setGame] = useState(null)
  const session = useContext(SessionContext)

  useEffect(() => {
    const getGame = async () => {
      try {
        // Langkah 1: Ambil data game
        const gameResponse = await fetch(`http://127.0.0.1:8000/api/game/${props.id}`);
        const gameData = await gameResponse.json();
        
        if(gameData.status === 'success') {
          setGame(gameData.game);
          
          // Langkah 2: Catat permainan (POST request)
          if (session.get.token) {
            await fetch(`http://127.0.0.1:8000/api/game/${props.id}/play`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${session.get.token}`
              }
            });
            // Kita tidak perlu mengambil hasil dari endpoint play
            // Karena kita sudah mendapatkan data game dari endpoint get
          }
        } else {
          setGame(null);
        }
      } catch (error) {
        console.error("Error fetching data:", error);
        setGame(null);
      }
    }
    
    if(!session.get.token){
      session.set({page: 'login', message : {type : 'danger', text: 'Anda harus login untuk memainkan game ini'}})
    } else {
      getGame();
    }
  }, [props.id, session]);
  
  return game == null ? (
    <div className="loader-container">
      <div className="spinner-border text-primary spinner-lg" role="status">
        <span className="visually-hidden">Loading...</span>
      </div>
      <p className="mt-3">Memuat game...</p>
    </div>
  ) : (
    <div className="play-container w-100 h-100 position-fixed top-0 start-0 m-0 p-0 bg-white">
      <div className="game-frame-container">
        <iframe className="game-frame" src={game.gameUrl + '/'}></iframe>
      </div>
      
      <div className="game-controls">
        <button className="back-button" onClick={()=>session.set({page: 'game', data: props.id})}>
          <i className="bi bi-arrow-left me-2"></i> Kembali
        </button>
        <div className="game-info-mini">
          <span className="game-title-mini">{game.name}</span>
        </div>
      </div>
    </div>
  )
}