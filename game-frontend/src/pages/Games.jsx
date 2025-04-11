import { useState, useEffect, useContext } from "react"
import SessionContext from '../components/Session'

export default function Games() {
  const session = useContext(SessionContext)
  const [games, setGames] = useState(null)
  const [categories, setCategories] = useState([])
  const [searchParams, setSearchParams] = useState({
    name: '',
    category: ''
  })
  const [isSearching, setIsSearching] = useState(false)
  
  // Fetch all games initially - tetap sama, tidak diubah
  useEffect(() => {
    const getGames = async () => {
      const response = await fetch(`http://127.0.0.1:8000/api/game`)
      const data = await response.json()
      if(data.status === 'success'){
        setGames(data.game)
      } else {
        setGames([])
      }
    }
    
    const getCategories = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/categories`)
        const data = await response.json()
        if(data.status === 'success'){
          setCategories(data.categories)
        }
      } catch (error) {
        console.error('Error fetching categories:', error)
      }
    }
    
    getGames()
    getCategories()
  }, [])
  
  // Handle input changes - tetap sama
  const handleInputChange = (e) => {
    const { name, value } = e.target
    setSearchParams(prev => ({
      ...prev,
      [name]: value
    }))
  }
  
  // Handle search form submission - tetap sama
  const handleSearch = async (e) => {
    e.preventDefault()
    setIsSearching(true)
    
    const queryParams = new URLSearchParams()
    if (searchParams.name) queryParams.append('name', searchParams.name)
    if (searchParams.category) queryParams.append('category', searchParams.category)
    
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/game/search?${queryParams.toString()}`)
      const data = await response.json()
      
      if (data.status === 'success') {
        setGames(data.games)
      } else {
        setGames([])
      }
    } catch (error) {
      console.error('Error searching games:', error)
      setGames([])
    } finally {
      setIsSearching(false)
    }
  }
  
  // Reset search - tetap sama
  const handleReset = async () => {
    setSearchParams({ name: '', category: '' })
    
    const response = await fetch(`http://127.0.0.1:8000/api/game`)
    const data = await response.json()
    if(data.status === 'success'){
      setGames(data.game)
    } else {
      setGames([])
    }
  }

  return (
    <div className="games-container">
      <div className="hero-section text-center mb-5">
        <h1 className="display-4 fw-bold text-gradient">Temukan Game Favorit</h1>
        <p className="lead">Jelajahi koleksi game menarik dan mainkan sekarang juga!</p>
      </div>
      
      {/* Search Form */}
      <div className="search-container p-4 mb-5">
        <form onSubmit={handleSearch} className="row g-3">
          <div className="col-md-5">
            <div className="input-group">
              <span className="input-group-text bg-transparent">
                <i className="bi bi-search"></i>
              </span>
              <input
                type="text"
                className="form-control custom-input"
                placeholder="Cari nama game..."
                name="name"
                value={searchParams.name}
                onChange={handleInputChange}
              />
            </div>
          </div>
          
          <div className="col-md-5">
            <div className="input-group">
              <span className="input-group-text bg-transparent">
                <i className="bi bi-grid"></i>
              </span>
              <select 
                className="form-select custom-input"
                name="category"
                value={searchParams.category}
                onChange={handleInputChange}
              >
                <option value="">Semua Kategori</option>
                {categories.map(category => (
                  <option key={category.id} value={category.id}>
                    {category.name}
                  </option>
                ))}
              </select>
            </div>
          </div>
          
          <div className="col-md-2 d-flex gap-2">
            <button type="submit" className="btn btn-primary btn-glow flex-grow-1" disabled={isSearching}>
              {isSearching ? <i className="bi bi-hourglass-split me-2"></i> : <i className="bi bi-search me-2"></i>}
              {isSearching ? 'Mencari...' : 'Cari'}
            </button>
            <button type="button" className="btn btn-outline-secondary" onClick={handleReset}>
              <i className="bi bi-x-lg"></i>
            </button>
          </div>
        </form>
      </div>

      {/* Game List */}
      <div className="game-list-container">
        {games === null ? (
          <div className="text-center my-5 py-5">
            <div className="spinner-border text-primary spinner-lg" role="status">
              <span className="visually-hidden">Loading...</span>
            </div>
            <p className="mt-3">Memuat game...</p>
          </div>
        ) : games.length === 0 ? (
          <div className="no-results text-center py-5">
            <i className="bi bi-emoji-frown display-1"></i>
            <p className="mt-3">Tidak ada game yang sesuai dengan kriteria pencarian.</p>
          </div>
        ) : (
          <div className="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4">
            {games.map((game) => (
              <div className="col" key={game.id}>
                <div className="game-card h-100">
                  <a
                    href={`#game/${game.id}`}
                    onClick={() => session.set({ page: 'game', data: game.id })}
                    className="game-card-link"
                  >
                    <div className="game-image-container">
                      <img
                        src={game.imgUrl}
                        className="game-image"
                        alt={game.name}
                      />
                      <div className="game-overlay">
                        <div className="play-icon">
                          <i className="bi bi-play-fill"></i>
                        </div>
                      </div>
                    </div>
                    <div className="game-info p-3">
                      <h5 className="game-title mb-1">{game.name}</h5>
                      <span className="game-category">{game.category_name || 'Game'}</span>
                    </div>
                  </a>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}