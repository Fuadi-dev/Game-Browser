export default function Footer() {
  return (
    <footer className="footer-container mt-auto py-4">
      <div className="container">
        <div className="row">
          <div className="col-md-4 mb-3 mb-md-0">
            <h5 className="text-gradient">GameZone</h5>
            <p className="small">Platform gaming terbaik dengan berbagai jenis game menarik untuk dimainkan.</p>
          </div>
          <div className="col-md-4 mb-3 mb-md-0">
            <h5 className="text-gradient">Tautan</h5>
            <ul className="list-unstyled">
              <li><a href="#home" className="footer-link">Beranda</a></li>
              <li><a href="#" className="footer-link">Tentang Kami</a></li>
              <li><a href="#" className="footer-link">Kebijakan Privasi</a></li>
            </ul>
          </div>
          <div className="col-md-4">
            <h5 className="text-gradient">Ikuti Kami</h5>
            <div className="social-icons">
              <a href="#" className="social-icon"><i className="bi bi-facebook"></i></a>
              <a href="#" className="social-icon"><i className="bi bi-twitter"></i></a>
              <a href="#" className="social-icon"><i className="bi bi-instagram"></i></a>
              <a href="#" className="social-icon"><i className="bi bi-discord"></i></a>
            </div>
          </div>
        </div>
        <hr className="mt-4" />
        <div className="text-center">
          <p className="mb-0 small">&copy; {new Date().getFullYear()} GameZone. Hak Cipta Dilindungi.</p>
        </div>
      </div>
    </footer>
  )
}
