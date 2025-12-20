# SME Digital Marketing Platform - Static Version

A modern, responsive static website ready for deployment to any hosting platform.

## 🚀 Quick Deploy

This website is 100% static (HTML, CSS, JavaScript) and can be deployed to:

- **Netlify**: Drag & drop the `static-version` folder
- **Vercel**: Import from GitHub or drag & drop
- **GitHub Pages**: Push to repo and enable Pages
- **Firebase Hosting**: `firebase deploy`
- **AWS S3**: Upload as static website
- **Any web hosting**: Upload via FTP

## 📁 File Structure

```
static-version/
├── index.html          # Home/Landing page
├── about.html          # About page
├── services.html       # Services page
├── contact.html        # Contact page
├── login.html          # Login page
├── register.html       # Registration page
├── css/
│   └── style.css      # Custom CSS with design system
└── js/
    └── components.js  # Navbar, Footer & Common JS
```

## 🎨 Technologies Used

- **HTML5** - Semantic markup
- **CSS3** - Custom properties, Flexbox, Grid
- **JavaScript (Vanilla)** - No dependencies
- **Tailwind CSS** - Via CDN (optional, already using custom CSS)
- **Font Awesome 6** - Icons via CDN
- **Google Fonts** - Inter & Noto Sans Bengali

## ✨ Features

### Design
- ✅ Modern gradient backgrounds
- ✅ Glassmorphism effects
- ✅ Smooth animations
- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Premium color palette

### Pages
- ✅ **Home**: Hero, Features, Services, Stats, Testimonials
- ✅ **About**: Mission, Vision, Team, Timeline
- ✅ **Services**: SEO, Ads, Graphics, Web Dev with pricing
- ✅ **Contact**: Form, Info cards, FAQ
- ✅ **Login**: Modern auth page with glassmorphism
- ✅ **Register**: Dual forms (Customer/Provider)

### Components
- ✅ Sticky navigation with mobile menu
- ✅ Comprehensive footer
- ✅ Form validation
- ✅ Scroll animations
- ✅ Active link highlighting

## 🔧 Customization

### Colors
Edit CSS custom properties in `css/style.css`:
```css
:root {
  --primary-600: #4f46e5;
  --secondary-600: #c026d3;
  /* ... more colors ... */
}
```

### Content
All content is in HTML files - simply edit the text in Bengali or English.

### Forms
Forms currently show alert messages. To integrate with backend:

1. **Option 1: FormSpree/Formsubmit**
   ```html
   <form action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
   ```

2. **Option 2: Netlify Forms**
   ```html
   <form name="contact" netlify>
   ```

3. **Option 3: Custom Backend**
   Update form submit handlers in HTML files to call your API

## 📱 Responsive Breakpoints

- Desktop: 1024px+
- Tablet: 768px - 1023px
- Mobile: < 768px

## 🌐 Browser Support

- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- Opera 76+

## 📝 Deployment Steps

### Netlify (Easiest)
1. Go to [Netlify](https://netlify.com)
2. Drag & drop the `static-version` folder
3. Done! Your site is live

### Vercel
1. Install Vercel CLI: `npm i -g vercel`
2. Navigate to `static-version` folder
3. Run `vercel`
4. Follow prompts

### GitHub Pages
1. Create a new repository
2. Upload all files from `static-version`
3. Go to Settings > Pages
4. Select main branch
5. Save

## 🔒 Notes

- All forms are client-side only
- No database integration included
- Authentication pages are UI only (no backend)
- Email functions need backend integration

## 🎯 Next Steps

To make this a fully functional platform:

1. **Add Backend**: Use Node.js, Python, or PHP
2. **Database**: MongoDB, MySQL, or PostgreSQL
3. **Authentication**: JWT, OAuth, or session-based
4. **Payment Integration**: Stripe, PayPal, or local gateways
5. **Email Service**: SendGrid, Mailgun, or SMTP

## 📧 Support

For questions or customization help, contact the development team.

---

**License**: Free to use for SME Digital Marketing Platform

**Version**: 1.0.0  
**Last Updated**: December 2024
