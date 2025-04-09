import { useContext } from 'react'
import SessionContext from '../../components/Session'

export default function Sidebar() {
  const session = useContext(SessionContext);
  return (
    <>
      <div className="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
        <div
          className="offcanvas-md offcanvas-end bg-body-tertiary"
          tabIndex={-1}
          id="sidebarMenu"
          aria-labelledby="sidebarMenuLabel"
        >
          <div className="offcanvas-header">
            <h5 className="offcanvas-title" id="sidebarMenuLabel">
              Gamers Hub
            </h5>
            <button
              type="button"
              className="btn-close"
              data-bs-dismiss="offcanvas"
              data-bs-target="#sidebarMenu"
              aria-label="Close"
            />
          </div>
          <div className="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
            <ul className="nav flex-column">
              <li className="nav-item">
                <a
                  className="nav-link d-flex align-items-center gap-2 active"
                  aria-current="page"
                  href="#"
                >
                 <span className="bi bi-house"></span>
                  Dashboard
                </a>
              </li>
              <li className="nav-item">
                <a className="nav-link d-flex align-items-center gap-2" href="#">
                <span className="bi bi-controller"></span>
                  Games
                </a>
              </li>
              { session.get.user.role == 'admin' ? (
              <li className="nav-item">
                 <a className="nav-link d-flex align-items-center gap-2" href="#users" onClick={() => session.set({page: 'users'})}>
                 <span className="bi bi-people"></span>
                  Users
                </a>
              </li>
              ):(<></>)}
            </ul>

            <hr className="my-3" />
            <ul className="nav flex-column mb-auto">
              <li className="nav-item">
                <a className="nav-link d-flex align-items-center gap-2" href="#">
                <span className="bi bi-gear"></span>
                  Settings
                </a>
              </li>
              <li className="nav-item">
                <a className="nav-link d-flex align-items-center gap-2" href="#logout" onClick={() => session.set({page: 'logout'})}>
                <span className="bi bi-box-arrow-left"></span>
                  Sign out
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </>
  )
}