import { useState, useEffect, useContext } from "react"
import SessionContext from '../components/Session'

export default function Games() {
  const session = useContext(SessionContext)
  const [games, setGames] = useState(null)
  
  useEffect(()=> {
    const getGames = async () => {
      const response = await fetch(`http://127.0.0.1:8000/api/game`)
      const data = await response.json()
      if(data.status == 'success'){
        setGames(data.game)
      }else{
        setGames([ ])
      }
    }
    getGames()
  },[])

  return (
    <>
      <h1>Games</h1>
      <div className="row">
        <div className="col">
          {games == null ? <>Loading...</> : games.map((game, i) => (
            // add space between each game
            <div className="col-2" key={i} style={{ margin: '10px' }}>
                <a key={i}
                  href={"#game/" + game.id}
                  onClick={() => session.set({ page: 'game', data: game.id })}
                >
                  <figure className="figure">
                    <img
                      src={game.imgUrl}
                      width="100"
                      height="100"
                      className="figure-img rounded-circle bg-light"
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
    </>
  )
}