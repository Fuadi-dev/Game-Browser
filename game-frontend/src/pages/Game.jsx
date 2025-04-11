import { useEffect, useState, useContext } from "react"
import SessionContext from '../components/Session'

export default function Game(props) {
  const [game, setGame] = useState(null)
  const [recommendations, setRecommendations] = useState([])
  const session = useContext(SessionContext)

  // Fungsi untuk mengambil detail game
  useEffect(() => {
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
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    }
    getGame()
  }, [props.id]) // Penting: dependency props.id memastikan rerender ketika ID berubah
  
  // Fungsi untuk navigasi ke game lain
  const handleGameClick = (gameId) => {
    // Reset state game dulu agar tampil loader
    setGame(null);
    setRecommendations([]);
    
    // Perbarui URL dengan game ID baru
    window.location.hash = `#game/${gameId}`;
    
    // Update session dengan page dan data baru
    session.set({page: 'game', data: gameId});
  };

  const fetchRecommendations = async (gameId) => {
    try {
      console.log("Fetching recommendations for game ID:", gameId);
      const response = await fetch(`http://127.0.0.1:8000/api/game/${gameId}/recommendations`);
      const data = await response.json();
      console.log("API response:", data);
      
      if(data.status === 'success'){
        setRecommendations(data.recommendations);
        console.log("Rekomendasi berhasil diambil:", data.recommendations.length, "game");
      } else {
        console.error("Error dari API:", data.message);
        setRecommendations([]);
      }
    } catch (error) {
      console.error("Error saat mengambil rekomendasi:", error);
      setRecommendations([]);
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

  return game == null ? (
    <div className="loader-container">
      <div className="spinner-border text-primary spinner-lg" role="status">
        <span className="visually-hidden">Loading...</span>
      </div>
      <p className="mt-3">Memuat detail game...</p>
    </div>
  ) : (
    <div className="game-detail-container">
      <div className="container">
        <div className="row">
          <div className="col-lg-8">
            <div className="game-showcase mb-4">
              <div className="position-relative game-cover-container">
                <img className="game-cover img-fluid rounded shadow" src={game.imgUrl} alt={game.name} />
                <button 
                  onClick={handlePlay}
                  className="play-button"
                >
                  <i className="bi bi-play-fill"></i>
                  <span>Main Sekarang</span>
                </button>
              </div>
            </div>
          </div>
          
          <div className="col-lg-4">
            <div className="game-info card">
              <div className="card-body">
                <h1 className="game-title h2 mb-3">{game.name}</h1>
                
                <div className="game-meta mb-4">
                  <div className="badge bg-primary me-2 p-2">
                    <i className="bi bi-people-fill me-1"></i> {game.played || '0'} pemain
                  </div>
                  {/* <div className="badge bg-success p-2">
                    <i className="bi bi-star-fill me-1"></i> {game.rating || '0'}/5
                  </div> */}
                </div>
                
                <div className="game-details">
                  <div className="detail-item">
                    <span className="detail-label">Developer:</span>
                    <span className="detail-value">{game.developer_name}</span>
                  </div>
                  <div className="detail-item">
                    <span className="detail-label">Versi:</span>
                    <span className="detail-value">{game.game_version}</span>
                  </div>
                  <div className="detail-item">
                    <span className="detail-label">Kategori:</span>
                  </div>
                  
                  {game.categories && game.categories.length > 0 && (
                    <div className="game-categories mt-3">
                      {game.categories.map(category => (
                        <span key={category.id} className="badge bg-secondary me-1 mb-1">
                          {category.name}
                        </span>
                      ))}
                    </div>
                  )}
                </div>

                <hr className="my-4" />

                <span className="detail-label">Description:</span>
                <br />
                <p className="game-description">{game.description}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      {/* Game Recommendations Section */}
      {recommendations && recommendations.length > 0 ? (
        <div className="recommendations-section mt-5 py-4">
          <div className="container">
            <h3 className="section-title mb-4">Game Serupa</h3>
            <div className="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
              {recommendations.map(recGame => (
                <div className="col" key={recGame.id}>
                  <div className="recommendation-card h-100">
                    <a 
                      href={`#game/${recGame.id}`}
                      onClick={(e) => {
                        e.preventDefault();
                        handleGameClick(recGame.id);
                      }}
                      className="recommendation-link"
                    >
                      <div className="recommendation-image-container">
                        <img
                          src={recGame.imgUrl}
                          className="recommendation-image"
                          alt={recGame.name}
                        />
                        <div className="recommendation-overlay">
                          <div className="mini-play-icon">
                            <i className="bi bi-info-circle"></i>
                          </div>
                        </div>
                      </div>
                      <div className="recommendation-info p-2">
                        <h6 className="recommendation-title mb-0">{recGame.name}</h6>
                      </div>
                    </a>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      ) : null}
    </div>
  )
}