<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <!-- Left Column - Company Info -->
        <div class="footer-section company-info">
          <div class="logo">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16 2C8.268 2 2 8.268 2 16C2 23.732 8.268 30 16 30C23.732 30 30 23.732 30 16C30 8.268 23.732 2 16 2Z" fill="#3B82F6"/>
              <path d="M22 12H18C17.448 12 17 12.448 17 13V21C17 21.552 17.448 22 18 22H22C22.552 22 23 21.552 23 21V13C23 12.448 22.552 12 22 12Z" fill="white"/>
              <path d="M14 10H10C9.448 10 9 10.448 9 11V21C9 21.552 9.448 22 10 22H14C14.552 22 15 21.552 15 21V11C15 10.448 14.552 10 14 10Z" fill="white"/>
            </svg>
            <span class="company-name">BookingApp</span>
          </div>
          <p class="company-description">Your trusted platform for seamless bookings and reservations.</p>
          <p class="copyright">&copy; 2025 BookingApp. All rights reserved.</p>
        </div>

        <!-- Center Column - Navigation Links -->
        <div class="footer-section navigation">
          <h4 class="section-title text-primary">Quick Links</h4>
          <ul class="nav-links">
            <li><a class="nav-link" @click="scrollToSection('hero')">Home</a></li>
            <li><a class="nav-link" @click="scrollToSection('features')">Features</a></li>
            <li><a class="nav-link" @click="scrollToSection('pricing')">Pricing</a></li>
            <li><a class="nav-link" @click="scrollToSection('testimonials')">Testimonials</a></li>
          </ul>
        </div>

        <!-- Right Column - Social Media & Newsletter -->
        <div class="footer-section connect">
          <h4 class="section-title text-primary">Connect With Us</h4>
          <div class="social-icons">
            <a href="#" class="social-icon facebook">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
              </svg>
            </a>
            <a href="#" class="social-icon twitter">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
              </svg>
            </a>
            <a href="#" class="social-icon instagram">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
              </svg>
            </a>
            <a href="#" class="social-icon linkedin">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
            </a>
          </div>

          <div class="newsletter">
            <h4 class="section-title text-primary">Stay Updated</h4>
            <p class="newsletter-text">Subscribe to our newsletter for the latest updates and offers.</p>
            <div class="newsletter-form">
              <input type="email" placeholder="Your email address" class="newsletter-input">
              <button class="newsletter-button">Subscribe</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'FooterComponent',
  methods: {
    scrollToSection(id) {
      // Agar already landing page par hain to direct scroll
      if (this.$route.path === "/") {
        const section = document.getElementById(id)
        if (section) {
          section.scrollIntoView({ behavior: "smooth" })
        }
      } else {
        // Agar kisi aur route par hain to pehle landing page par le jao, fir scroll
        this.$router.push("/").then(() => {
          setTimeout(() => {
            const section = document.getElementById(id)
            if (section) {
              section.scrollIntoView({ behavior: "smooth" })
            }
          }, 500)
        })
      }
    }
  }
}
</script>

<style scoped>
.footer {
  background: linear-gradient(135deg, #ffffff 0%, #edeff2 100%);
  color: #e2e8f0;
  padding: 3rem 0 1.5rem;
  position: relative;
  overflow: hidden;
}

.footer::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, #848688, transparent);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  position: relative;
  z-index: 1;
}

.footer-content {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 2rem;
}

.footer-section {
  flex: 1;
  min-width: 250px;
}

.company-info {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.company-name {
  font-size: 1.5rem;
  font-weight: 700;
  background: linear-gradient(90deg, #3B82F6, #60A5FA);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.company-description {
  color: #050505;
  line-height: 1.6;
  max-width: 280px;
}

.copyright {
  color: #050505;
  font-size: 0.875rem;
  margin-top: auto;
}

.section-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1.25rem;
  color: #e2e8f0;
  position: relative;
  padding-bottom: 0.5rem;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 2px;
  background: linear-gradient(90deg, #3B82F6, transparent);
}

.nav-links {
  list-style: none;
  padding: 0;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.75rem;
  cursor: pointer;
}

.nav-link {
  color: #050505;
  text-decoration: none;
  transition: all 0.3s ease;
  display: inline-block;
  position: relative;
  font-size: 0.95rem;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background: #3B82F6;
  transition: width 0.3s ease;
}

.nav-link:hover {
  color: #3B82F6;
  transform: translateX(5px);
}

.nav-link:hover::after {
  width: 100%;
}

.social-icons {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.social-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
  color: #3d3d3d;
}

.social-icon:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.facebook:hover {
  background: #3b5998;
  color: white;
}

.twitter:hover {
  background: #1da1f2;
  color: white;
}

.instagram:hover {
  background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
  color: white;
}

.linkedin:hover {
  background: #0077b5;
  color: white;
}

.newsletter {
  margin-top: 1.5rem;
}

.newsletter-text {
  color: #050505;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.newsletter-form {
  display: flex;
  gap: 0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.newsletter-input {
  flex: 1;
  padding: 0.75rem 1rem;
  border: none;
  background: rgba(255, 255, 255, 0.05);
  color: #706e6e;
  font-size: 0.9rem;
  outline: none;
  transition: all 0.3s ease;
}

.newsletter-input::placeholder {
  color: #a0aec0;
}

.newsletter-input:focus {
  background: rgba(255, 255, 255, 0.1);
}

.newsletter-button {
  padding: 0.75rem 1.25rem;
  background: linear-gradient(90deg, #3B82F6, #2563EB);
  color: white;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.newsletter-button:hover {
  background: linear-gradient(90deg, #2563EB, #1D4ED8);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    gap: 2.5rem;
  }

  .nav-links {
    grid-template-columns: 1fr;
  }

  .newsletter-form {
    flex-direction: column;
    border-radius: 8px;
  }

  .newsletter-button {
    border-radius: 0 0 8px 8px;
  }
}
</style>
