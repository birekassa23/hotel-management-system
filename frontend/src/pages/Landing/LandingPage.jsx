import React, { useState, useEffect } from 'react';
import { api } from '../../services/api';
import Navbar from '../../components/Navbar';
import Modal from '../../components/Modal';
import {
  Bed,
  Users,
  Calendar,
  CheckCircle,
  Clock,
  MapPin,
  Phone,
  Mail,
  Utensils,
  Wine,
  Sparkles,
  Search,
  Star,
  Shield,
  Coffee,
  Tv,
  Wifi,
  ChevronRight
} from 'lucide-react';

export default function LandingPage({ onNavigate }) {
  const [rooms, setRooms] = useState([]);
  const [halls, setHalls] = useState([]);
  const [foods, setFoods] = useState([]);
  const [beverages, setBeverages] = useState([]);
  const [feedbackList, setFeedbackList] = useState([]);
  const [loading, setLoading] = useState(true);

  // Booking Modal State
  const [isBookingOpen, setIsBookingOpen] = useState(false);
  const [selectedRoom, setSelectedRoom] = useState(null);
  const [bookingType, setBookingType] = useState('room'); // 'room' or 'hall'
  const [bookingForm, setBookingForm] = useState({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    kebele_id: '',
    checkin_date: new Date().toISOString().split('T')[0],
    checkout_date: new Date(Date.now() + 86400000 * 2).toISOString().split('T')[0],
    guests_count: 1,
    notes: ''
  });
  const [bookingSuccess, setBookingSuccess] = useState(null);

  // Lookup Modal State
  const [isLookupOpen, setIsLookupOpen] = useState(false);
  const [lookupQuery, setLookupQuery] = useState('');
  const [lookupResult, setLookupResult] = useState(null);
  const [lookupLoading, setLookupLoading] = useState(false);

  // Review Submission State
  const [reviewForm, setReviewForm] = useState({ guest_name: '', guest_email: '', rating: 5, comment: '' });
  const [reviewSubmitted, setReviewSubmitted] = useState(false);

  useEffect(() => {
    async function loadData() {
      try {
        const [roomsData, hallsData, foodsData, bevsData, feedbackData] = await Promise.all([
          api.getRooms('?status=all'),
          api.getHalls(),
          api.getFoods(),
          api.getBeverages(),
          api.getFeedback()
        ]);
        setRooms(roomsData);
        setHalls(hallsData);
        setFoods(foodsData);
        setBeverages(bevsData);
        setFeedbackList(feedbackData);
      } catch (err) {
        console.error('Error fetching landing data:', err);
      } finally {
        setLoading(false);
      }
    }
    loadData();
  }, []);

  const handleOpenRoomBooking = (room) => {
    setSelectedRoom(room);
    setBookingType('room');
    setBookingSuccess(null);
    setIsBookingOpen(true);
  };

  const handleOpenHallBooking = (hall) => {
    setSelectedRoom(hall);
    setBookingType('hall');
    setBookingSuccess(null);
    setIsBookingOpen(true);
  };

  const handleBookingSubmit = async (e) => {
    e.preventDefault();
    try {
      if (bookingType === 'room') {
        const payload = {
          ...bookingForm,
          room_id: selectedRoom.id,
          room_type: selectedRoom.type,
          room_price: selectedRoom.price,
          assigned_by: 'Online Guest Portal'
        };
        const res = await api.createRoomReservation(payload);
        setBookingSuccess(res.reservation);
      } else {
        const payload = {
          ...bookingForm,
          hall_id: selectedRoom.id,
          hall_name: selectedRoom.name,
          hall_price: selectedRoom.price,
          event_type: 'Conference / Event',
          assigned_by: 'Online Guest Portal'
        };
        const res = await api.createHallReservation(payload);
        setBookingSuccess(res.reservation);
      }
    } catch (err) {
      alert(err.message || 'Error processing reservation');
    }
  };

  const handleLookup = async (e) => {
    e.preventDefault();
    if (!lookupQuery.trim()) return;
    setLookupLoading(true);
    setLookupResult(null);
    try {
      const isEmail = lookupQuery.includes('@');
      const param = isEmail ? `email=${encodeURIComponent(lookupQuery.trim())}` : `code=${encodeURIComponent(lookupQuery.trim())}`;
      const res = await api.lookupBooking(param);
      setLookupResult(res);
    } catch (err) {
      alert(err.message || 'Lookup failed');
    } finally {
      setLookupLoading(false);
    }
  };

  const handleReviewSubmit = async (e) => {
    e.preventDefault();
    if (!reviewForm.guest_name || !reviewForm.comment) return;
    try {
      const res = await api.submitFeedback(reviewForm);
      setFeedbackList([res.review, ...feedbackList]);
      setReviewSubmitted(true);
      setReviewForm({ guest_name: '', guest_email: '', rating: 5, comment: '' });
      setTimeout(() => setReviewSubmitted(false), 5000);
    } catch (err) {
      alert(err.message || 'Error posting review');
    }
  };

  return (
    <div style={{ minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>
      <Navbar 
        onNavigate={onNavigate} 
        onOpenBookingModal={() => handleOpenRoomBooking(rooms[0] || { id: 1, type: 'Deluxe Suite', price: 1900 })}
        onOpenLookupModal={() => setIsLookupOpen(true)}
      />

      {/* Hero Section */}
      <section style={{
        position: 'relative',
        minHeight: '85vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '80px 24px',
        textAlign: 'center',
        background: `linear-gradient(180deg, rgba(9, 13, 22, 0.7) 0%, rgba(9, 13, 22, 0.95) 100%), url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat`
      }}>
        <div style={{ maxWidth: '900px', margin: '0 auto', zIndex: 2 }}>
          <div style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '6px 16px',
            borderRadius: 'var(--radius-full)',
            background: 'rgba(212, 175, 55, 0.15)',
            border: '1px solid var(--border-gold)',
            color: 'var(--gold-light)',
            fontSize: '0.85rem',
            fontWeight: '600',
            marginBottom: '24px'
          }}>
            <Sparkles size={16} color="var(--gold-primary)" />
            <span>South West Ethiopia's Premier 5-Star Destination</span>
          </div>

          <h1 style={{
            fontSize: 'clamp(2.5rem, 6vw, 4.2rem)',
            fontWeight: '800',
            lineHeight: 1.15,
            marginBottom: '20px',
            color: '#ffffff',
            textShadow: '0 4px 20px rgba(0,0,0,0.8)'
          }}>
            Experience Timeless Elegance & Warm Ethiopian Hospitality
          </h1>

          <p style={{
            fontSize: 'clamp(1rem, 2vw, 1.25rem)',
            color: 'var(--text-muted)',
            maxWidth: '720px',
            margin: '0 auto 36px',
            fontWeight: '400'
          }}>
            Located in picturesque Teppi, Ehitmamachochi Hotel pairs world-class accommodation, state-of-the-art conference facilities, and authentic Ethiopian gastronomy.
          </p>

          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '16px', flexWrap: 'wrap' }}>
            <button
              onClick={() => handleOpenRoomBooking(rooms[0] || { id: 1, type: 'Deluxe Suite', price: 1900 })}
              className="btn btn-gold"
              style={{ padding: '14px 32px', fontSize: '1.05rem', borderRadius: '8px' }}
            >
              <Calendar size={20} /> Reserve Your Sanctuary
            </button>
            <button
              onClick={() => onNavigate('dashboard')}
              className="btn btn-outline"
              style={{ padding: '14px 28px', fontSize: '1.05rem', borderRadius: '8px' }}
            >
              <Shield size={18} color="var(--gold-primary)" /> Explore Management Suite
            </button>
          </div>

          {/* Quick Metrics */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(160px, 1fr))',
            gap: '20px',
            marginTop: '60px',
            padding: '20px',
            background: 'rgba(18, 26, 45, 0.65)',
            backdropFilter: 'blur(12px)',
            borderRadius: 'var(--radius-md)',
            border: '1px solid var(--border-light)'
          }}>
            <div>
              <div style={{ fontSize: '1.75rem', fontWeight: '800', color: 'var(--gold-primary)' }}>50+</div>
              <div style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>Luxury Rooms & Suites</div>
            </div>
            <div>
              <div style={{ fontSize: '1.75rem', fontWeight: '800', color: 'var(--emerald)' }}>4</div>
              <div style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>Grand Conference Halls</div>
            </div>
            <div>
              <div style={{ fontSize: '1.75rem', fontWeight: '800', color: 'var(--gold-light)' }}>100%</div>
              <div style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>Authentic Ethiopian Dining</div>
            </div>
            <div>
              <div style={{ fontSize: '1.75rem', fontWeight: '800', color: 'var(--sapphire)' }}>24 / 7</div>
              <div style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>Concierge & Room Service</div>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Rooms & Suites */}
      <section id="rooms" style={{ padding: '80px 24px', maxWidth: '1280px', margin: '0 auto', width: '100%' }}>
        <div style={{ textAlign: 'center', marginBottom: '48px' }}>
          <div style={{ color: 'var(--gold-primary)', fontWeight: '700', fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '0.15em', marginBottom: '8px' }}>
            Accommodations
          </div>
          <h2 style={{ fontSize: '2.4rem', fontWeight: '800' }}>Rooms & Presidential Suites</h2>
          <p style={{ color: 'var(--text-muted)', maxWidth: '600px', margin: '8px auto 0' }}>
            Immerse yourself in supreme comfort, panoramic mountain vistas, plush bedding, and high-speed fiber connectivity.
          </p>
        </div>

        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fill, minmax(340px, 1fr))',
          gap: '28px'
        }}>
          {rooms.map((room) => (
            <div key={room.id} className="glass-card" style={{ padding: '0', overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
              <div style={{ height: '210px', position: 'relative', overflow: 'hidden' }}>
                <img
                  src={room.image_url || 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80'}
                  alt={room.type}
                  style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.4s ease' }}
                  onMouseOver={(e) => e.target.style.transform = 'scale(1.05)'}
                  onMouseOut={(e) => e.target.style.transform = 'scale(1)'}
                />
                <div style={{ position: 'absolute', top: '14px', right: '14px' }}>
                  <span className={`badge badge-${room.status}`}>
                    {room.status === 'free' ? 'Available' : room.status}
                  </span>
                </div>
                <div style={{ position: 'absolute', bottom: '12px', left: '14px', background: 'rgba(9, 13, 22, 0.85)', backdropFilter: 'blur(6px)', padding: '4px 10px', borderRadius: '4px', fontSize: '0.75rem', fontWeight: '600', color: 'var(--text-muted)' }}>
                  Room {room.room_number} • Floor {room.floor}
                </div>
              </div>

              <div style={{ padding: '22px', flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
                <div>
                  <h3 style={{ fontSize: '1.25rem', fontWeight: '700', marginBottom: '8px' }}>{room.type}</h3>
                  <p style={{ fontSize: '0.875rem', color: 'var(--text-muted)', marginBottom: '16px' }}>{room.description}</p>
                  
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '8px', marginBottom: '20px' }}>
                    {room.amenities.split(',').map((am, i) => (
                      <span key={i} style={{ fontSize: '0.75rem', background: 'rgba(255, 255, 255, 0.05)', padding: '3px 8px', borderRadius: '4px', color: 'var(--text-muted)' }}>
                        ✓ {am.trim()}
                      </span>
                    ))}
                  </div>
                </div>

                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderTop: '1px solid var(--border-light)', paddingTop: '16px' }}>
                  <div>
                    <span style={{ fontSize: '1.4rem', fontWeight: '800', color: 'var(--gold-light)' }}>{room.price} ETB</span>
                    <span style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}> / night</span>
                  </div>

                  <button
                    onClick={() => handleOpenRoomBooking(room)}
                    disabled={room.status === 'occupied'}
                    className="btn btn-gold btn-sm"
                    style={{ opacity: room.status === 'occupied' ? 0.5 : 1 }}
                  >
                    {room.status === 'occupied' ? 'Occupied' : 'Book Room'}
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Meeting Halls Section */}
      <section id="halls" style={{ padding: '80px 24px', background: 'rgba(14, 20, 36, 0.5)', borderTop: '1px solid var(--border-light)', borderBottom: '1px solid var(--border-light)' }}>
        <div style={{ maxWidth: '1280px', margin: '0 auto' }}>
          <div style={{ textAlign: 'center', marginBottom: '48px' }}>
            <div style={{ color: 'var(--gold-primary)', fontWeight: '700', fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '0.15em', marginBottom: '8px' }}>
              Events & Summits
            </div>
            <h2 style={{ fontSize: '2.4rem', fontWeight: '800' }}>Grand Meeting & Conference Halls</h2>
            <p style={{ color: 'var(--text-muted)', maxWidth: '600px', margin: '8px auto 0' }}>
              Equipped with crystal acoustic audio, projection setups, translation booths, and customizable banquet dining.
            </p>
          </div>

          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fill, minmax(340px, 1fr))',
            gap: '28px'
          }}>
            {halls.map((hall) => (
              <div key={hall.id} className="glass-card" style={{ padding: '0', overflow: 'hidden', display: 'flex', flexDirection: 'column' }}>
                <div style={{ height: '200px', position: 'relative' }}>
                  <img
                    src={hall.image_url || 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80'}
                    alt={hall.name}
                    style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                  />
                  <div style={{ position: 'absolute', bottom: '12px', left: '14px', background: 'rgba(9, 13, 22, 0.85)', backdropFilter: 'blur(6px)', padding: '4px 10px', borderRadius: '4px', fontSize: '0.75rem', fontWeight: '600', color: 'var(--gold-light)' }}>
                    Capacity: Up to {hall.capacity} Guests
                  </div>
                </div>

                <div style={{ padding: '22px', flex: 1, display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
                  <div>
                    <h3 style={{ fontSize: '1.25rem', fontWeight: '700', marginBottom: '6px' }}>{hall.name}</h3>
                    <div style={{ fontSize: '0.8rem', color: 'var(--gold-primary)', fontWeight: '600', marginBottom: '10px' }}>{hall.type}</div>
                    <p style={{ fontSize: '0.875rem', color: 'var(--text-muted)', marginBottom: '16px' }}>{hall.description}</p>
                    <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)', marginBottom: '16px' }}>
                      <strong>Amenities:</strong> {hall.amenities}
                    </div>
                  </div>

                  <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderTop: '1px solid var(--border-light)', paddingTop: '16px' }}>
                    <div>
                      <span style={{ fontSize: '1.35rem', fontWeight: '800', color: 'var(--gold-light)' }}>{hall.price} ETB</span>
                      <span style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}> / event</span>
                    </div>

                    <button
                      onClick={() => handleOpenHallBooking(hall)}
                      className="btn btn-gold btn-sm"
                    >
                      Reserve Hall
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Dining & Beverage Experience */}
      <section id="dining" style={{ padding: '80px 24px', maxWidth: '1280px', margin: '0 auto', width: '100%' }}>
        <div style={{ textAlign: 'center', marginBottom: '48px' }}>
          <div style={{ color: 'var(--gold-primary)', fontWeight: '700', fontSize: '0.85rem', textTransform: 'uppercase', letterSpacing: '0.15em', marginBottom: '8px' }}>
            Gastronomy
          </div>
          <h2 style={{ fontSize: '2.4rem', fontWeight: '800' }}>Exquisite Dining & Bar Lounge</h2>
          <p style={{ color: 'var(--text-muted)', maxWidth: '600px', margin: '8px auto 0' }}>
            Prepared by master chefs using prime highland ingredients, fragrant Ethiopian butter, and fresh seasonal harvests.
          </p>
        </div>

        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))',
          gap: '24px'
        }}>
          {foods.slice(0, 4).map((food) => (
            <div key={food.id} className="glass-card" style={{ padding: '16px' }}>
              <img
                src={food.image_url || 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80'}
                alt={food.name}
                style={{ width: '100%', height: '160px', objectFit: 'cover', borderRadius: '8px', marginBottom: '14px' }}
              />
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '6px' }}>
                <h4 style={{ fontSize: '1.05rem', fontWeight: '700' }}>{food.name}</h4>
                <span style={{ fontWeight: '800', color: 'var(--gold-primary)', fontSize: '1.1rem' }}>{food.price} ETB</span>
              </div>
              <p style={{ fontSize: '0.8rem', color: 'var(--text-muted)' }}>{food.description}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Guest Reviews & Feedback Submission */}
      <section style={{ padding: '80px 24px', background: 'rgba(14, 20, 36, 0.4)', borderTop: '1px solid var(--border-light)' }}>
        <div style={{ maxWidth: '1100px', margin: '0 auto' }}>
          <div style={{ textAlign: 'center', marginBottom: '40px' }}>
            <h2 style={{ fontSize: '2rem', fontWeight: '800' }}>Guest Testimonials & Reviews</h2>
            <p style={{ color: 'var(--text-muted)' }}>Read what dignitaries and travelers say about their experience with us.</p>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '20px', marginBottom: '40px' }}>
            {feedbackList.slice(0, 3).map((f) => (
              <div key={f.id} className="glass-card">
                <div style={{ display: 'flex', alignItems: 'center', gap: '4px', marginBottom: '10px' }}>
                  {[...Array(f.rating || 5)].map((_, i) => (
                    <Star key={i} size={16} fill="var(--gold-primary)" color="var(--gold-primary)" />
                  ))}
                </div>
                <p style={{ fontSize: '0.9rem', color: 'var(--text-main)', fontStyle: 'italic', marginBottom: '16px' }}>
                  "{f.comment}"
                </p>
                <div style={{ fontWeight: '700', fontSize: '0.875rem', color: 'var(--gold-light)' }}>
                  {f.guest_name}
                </div>
                <div style={{ fontSize: '0.75rem', color: 'var(--text-dim)' }}>
                  Verified Hotel Stay
                </div>
              </div>
            ))}
          </div>

          {/* Leave a review box */}
          <div className="glass-card" style={{ maxWidth: '650px', margin: '0 auto', padding: '24px' }}>
            <h3 style={{ fontSize: '1.15rem', fontWeight: '700', marginBottom: '16px' }}>Share Your Experience</h3>
            {reviewSubmitted && (
              <div style={{ padding: '10px', background: 'rgba(16, 185, 129, 0.2)', border: '1px solid var(--emerald)', borderRadius: '6px', color: '#34d399', marginBottom: '14px', fontSize: '0.85rem' }}>
                Thank you! Your feedback has been published.
              </div>
            )}
            <form onSubmit={handleReviewSubmit}>
              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px', marginBottom: '14px' }}>
                <input
                  type="text"
                  placeholder="Your Name"
                  className="form-control"
                  value={reviewForm.guest_name}
                  onChange={(e) => setReviewForm({ ...reviewForm, guest_name: e.target.value })}
                  required
                />
                <input
                  type="email"
                  placeholder="Your Email (Optional)"
                  className="form-control"
                  value={reviewForm.guest_email}
                  onChange={(e) => setReviewForm({ ...reviewForm, guest_email: e.target.value })}
                />
              </div>
              <textarea
                placeholder="Write your review here..."
                rows="3"
                className="form-control"
                value={reviewForm.comment}
                onChange={(e) => setReviewForm({ ...reviewForm, comment: e.target.value })}
                style={{ marginBottom: '14px' }}
                required
              />
              <button type="submit" className="btn btn-gold btn-sm">
                Submit Review
              </button>
            </form>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer style={{
        marginTop: 'auto',
        background: '#070a12',
        borderTop: '1px solid var(--border-light)',
        padding: '50px 24px 30px'
      }}>
        <div style={{ maxWidth: '1280px', margin: '0 auto', display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '32px', marginBottom: '40px' }}>
          <div>
            <h4 style={{ color: 'var(--gold-primary)', fontSize: '1.1rem', marginBottom: '14px' }}>EHITMAMACHOCHI</h4>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.85rem', lineHeight: 1.6 }}>
              The highest standard of hospitality, executive meetings, and luxury stays in Teppi, Ethiopia.
            </p>
          </div>
          <div>
            <h5 style={{ fontSize: '0.95rem', marginBottom: '14px' }}>Location</h5>
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--text-muted)', fontSize: '0.85rem', marginBottom: '8px' }}>
              <MapPin size={16} color="var(--gold-primary)" />
              Ayermeda above post Kembridge Academy, Teppi, Ethiopia
            </div>
          </div>
          <div>
            <h5 style={{ fontSize: '0.95rem', marginBottom: '14px' }}>Direct Inquiries</h5>
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--text-muted)', fontSize: '0.85rem', marginBottom: '8px' }}>
              <Phone size={16} color="var(--gold-primary)" />
              +251 904 9314
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', color: 'var(--text-muted)', fontSize: '0.85rem' }}>
              <Mail size={16} color="var(--gold-primary)" />
              contact@ehitmamachochihotel.com
            </div>
          </div>
          <div>
            <h5 style={{ fontSize: '0.95rem', marginBottom: '14px' }}>Staff Access</h5>
            <button onClick={() => onNavigate('login')} className="btn btn-outline btn-sm">
              Employee Sign In
            </button>
          </div>
        </div>
        <div style={{ textAlign: 'center', borderTop: '1px solid var(--border-light)', paddingTop: '20px', color: 'var(--text-dim)', fontSize: '0.775rem' }}>
          © 2026 Ehitmamachochi Hotel Management System. Built with React & Node.js/Express.
        </div>
      </footer>

      {/* Online Reservation Modal */}
      <Modal
        isOpen={isBookingOpen}
        onClose={() => setIsBookingOpen(false)}
        title={bookingSuccess ? 'Booking Confirmed!' : `Reserve ${selectedRoom?.name || selectedRoom?.type || 'Room'}`}
      >
        {bookingSuccess ? (
          <div style={{ textAlign: 'center', padding: '16px 0' }}>
            <div style={{ width: '64px', height: '64px', borderRadius: '50%', background: 'rgba(16, 185, 129, 0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
              <CheckCircle size={36} color="var(--emerald)" />
            </div>
            <h3 style={{ fontSize: '1.4rem', fontWeight: '800', marginBottom: '8px' }}>Reservation Registered!</h3>
            <p style={{ color: 'var(--text-muted)', fontSize: '0.9rem', marginBottom: '20px' }}>
              Your reservation reference code is:
            </p>
            <div style={{ fontSize: '1.8rem', fontWeight: '800', color: 'var(--gold-primary)', padding: '12px', background: 'rgba(212, 175, 55, 0.1)', border: '1px dashed var(--border-gold)', borderRadius: '8px', marginBottom: '20px' }}>
              {bookingSuccess.booking_code}
            </div>
            <p style={{ fontSize: '0.825rem', color: 'var(--text-dim)', marginBottom: '24px' }}>
              Save this code to check your booking status or present it to the Receptionist upon check-in.
            </p>
            <button onClick={() => setIsBookingOpen(false)} className="btn btn-gold" style={{ width: '100%' }}>
              Done
            </button>
          </div>
        ) : (
          <form onSubmit={handleBookingSubmit}>
            <div style={{ marginBottom: '16px', padding: '12px', background: 'rgba(255, 255, 255, 0.03)', borderRadius: '6px', border: '1px solid var(--border-light)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div>
                <div style={{ fontWeight: '700' }}>{selectedRoom?.name || selectedRoom?.type}</div>
                <div style={{ fontSize: '0.8rem', color: 'var(--text-muted)' }}>{selectedRoom?.description?.slice(0, 60)}...</div>
              </div>
              <div style={{ fontSize: '1.2rem', fontWeight: '800', color: 'var(--gold-light)' }}>
                {selectedRoom?.price} ETB
              </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
              <div className="form-group">
                <label className="form-label">Full Name *</label>
                <input
                  type="text"
                  required
                  placeholder="e.g. Almaz Bekele"
                  className="form-control"
                  value={bookingForm.customer_name}
                  onChange={(e) => setBookingForm({ ...bookingForm, customer_name: e.target.value })}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Phone Number *</label>
                <input
                  type="tel"
                  required
                  placeholder="+251 9..."
                  className="form-control"
                  value={bookingForm.customer_phone}
                  onChange={(e) => setBookingForm({ ...bookingForm, customer_phone: e.target.value })}
                />
              </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
              <div className="form-group">
                <label className="form-label">Email Address</label>
                <input
                  type="email"
                  placeholder="guest@domain.com"
                  className="form-control"
                  value={bookingForm.customer_email}
                  onChange={(e) => setBookingForm({ ...bookingForm, customer_email: e.target.value })}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Kebele / ID / Passport</label>
                <input
                  type="text"
                  placeholder="ID Number"
                  className="form-control"
                  value={bookingForm.kebele_id}
                  onChange={(e) => setBookingForm({ ...bookingForm, kebele_id: e.target.value })}
                />
              </div>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px' }}>
              <div className="form-group">
                <label className="form-label">Check-in Date *</label>
                <input
                  type="date"
                  required
                  className="form-control"
                  value={bookingForm.checkin_date}
                  onChange={(e) => setBookingForm({ ...bookingForm, checkin_date: e.target.value })}
                />
              </div>
              <div className="form-group">
                <label className="form-label">Check-out Date *</label>
                <input
                  type="date"
                  required
                  className="form-control"
                  value={bookingForm.checkout_date}
                  onChange={(e) => setBookingForm({ ...bookingForm, checkout_date: e.target.value })}
                />
              </div>
            </div>

            <div className="form-group">
              <label className="form-label">Special Requests / Notes</label>
              <textarea
                rows="2"
                placeholder="Airport pickup, dietary needs, or high-floor preference..."
                className="form-control"
                value={bookingForm.notes}
                onChange={(e) => setBookingForm({ ...bookingForm, notes: e.target.value })}
              />
            </div>

            <div style={{ display: 'flex', gap: '12px', marginTop: '20px' }}>
              <button type="button" onClick={() => setIsBookingOpen(false)} className="btn btn-outline" style={{ flex: 1 }}>
                Cancel
              </button>
              <button type="submit" className="btn btn-gold" style={{ flex: 2 }}>
                Confirm Reservation
              </button>
            </div>
          </form>
        )}
      </Modal>

      {/* Booking Status Lookup Modal */}
      <Modal
        isOpen={isLookupOpen}
        onClose={() => setIsLookupOpen(false)}
        title="Check Reservation Status"
      >
        <form onSubmit={handleLookup} style={{ marginBottom: '20px' }}>
          <div style={{ display: 'flex', gap: '10px' }}>
            <input
              type="text"
              placeholder="Enter Booking Code (e.g. RES-1029) or Email"
              className="form-control"
              value={lookupQuery}
              onChange={(e) => setLookupQuery(e.target.value)}
              required
            />
            <button type="submit" className="btn btn-gold" disabled={lookupLoading}>
              <Search size={16} /> Look Up
            </button>
          </div>
        </form>

        {lookupLoading && (
          <div style={{ textAlign: 'center', padding: '20px', color: 'var(--text-muted)' }}>
            Searching database...
          </div>
        )}

        {lookupResult && (
          <div>
            {lookupResult.roomBooking ? (
              <div className="glass-card" style={{ padding: '16px', marginBottom: '14px' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
                  <span style={{ fontWeight: '800', color: 'var(--gold-primary)', fontSize: '1.1rem' }}>
                    {lookupResult.roomBooking.booking_code}
                  </span>
                  <span className={`badge badge-${lookupResult.roomBooking.status}`}>
                    {lookupResult.roomBooking.status}
                  </span>
                </div>
                <div style={{ fontSize: '0.875rem', marginBottom: '4px' }}>
                  <strong>Guest:</strong> {lookupResult.roomBooking.customer_name}
                </div>
                <div style={{ fontSize: '0.875rem', marginBottom: '4px' }}>
                  <strong>Room Type:</strong> {lookupResult.roomBooking.room_type}
                </div>
                <div style={{ fontSize: '0.875rem', marginBottom: '4px' }}>
                  <strong>Dates:</strong> {lookupResult.roomBooking.checkin_date} to {lookupResult.roomBooking.checkout_date}
                </div>
                <div style={{ fontSize: '0.875rem' }}>
                  <strong>Payment Status:</strong> <span className={`badge badge-${lookupResult.roomBooking.payment_status}`}>{lookupResult.roomBooking.payment_status}</span>
                </div>
              </div>
            ) : (
              <div style={{ textAlign: 'center', padding: '16px', color: 'var(--text-muted)' }}>
                No active booking found matching "{lookupQuery}".
              </div>
            )}
          </div>
        )}
      </Modal>
    </div>
  );
}
