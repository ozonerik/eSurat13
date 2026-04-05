import React, { useState, useEffect } from 'react';
import { 
  Mail, 
  FileText, 
  ShieldCheck, 
  Zap, 
  BarChart3, 
  ChevronRight, 
  Menu, 
  X,
  ArrowRight,
  CheckCircle2,
  Inbox,
  Send,
  Search
} from 'lucide-react';

const App = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const features = [
    {
      title: "Manajemen Surat Masuk",
      description: "Catat dan arsipkan setiap surat masuk dengan detail pengirim dan perihal yang terorganisir.",
      icon: <Inbox className="w-6 h-6 text-blue-600" />
    },
    {
      title: "Manajemen Surat Keluar",
      description: "Kelola draf dan pengiriman surat keluar secara sistematis dengan nomor agenda otomatis.",
      icon: <Send className="w-6 h-6 text-blue-600" />
    },
    {
      title: "Pencarian Cepat",
      description: "Temukan dokumen yang Anda cari dalam hitungan detik dengan sistem indeksasi cerdas.",
      icon: <Search className="w-6 h-6 text-blue-600" />
    },
    {
      title: "Dashboard Statistik",
      description: "Pantau volume surat menyurat harian melalui visualisasi data yang informatif.",
      icon: <BarChart3 className="w-6 h-6 text-blue-600" />
    },
    {
      title: "Keamanan Data",
      description: "Sistem otentikasi berlapis memastikan hanya pihak berwenang yang dapat mengakses arsip.",
      icon: <ShieldCheck className="w-6 h-6 text-blue-600" />
    },
    {
      title: "Performa Cepat",
      description: "Dibangun dengan teknologi modern untuk memastikan akses data tanpa hambatan.",
      icon: <Zap className="w-6 h-6 text-blue-600" />
    }
  ];

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-900">
      {/* Navigation */}
      <nav className={`fixed w-full z-50 transition-all duration-300 ${scrolled ? 'bg-white/80 backdrop-blur-md shadow-sm py-3' : 'bg-transparent py-5'}`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
          <div className="flex items-center gap-2">
            <div className="bg-blue-600 p-2 rounded-lg">
              <Mail className="w-6 h-6 text-white" />
            </div>
            <span className="text-xl font-bold tracking-tight text-blue-900">eSurat<span className="text-blue-600">13</span></span>
          </div>
          
          <div className="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="#features" className="hover:text-blue-600 transition-colors">Fitur</a>
            <a href="#about" className="hover:text-blue-600 transition-colors">Tentang</a>
            <button className="bg-blue-600 text-white px-5 py-2.5 rounded-full hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
              Coba Sekarang
            </button>
          </div>

          <button className="md:hidden p-2" onClick={() => setIsMenuOpen(!isMenuOpen)}>
            {isMenuOpen ? <X /> : <Menu />}
          </button>
        </div>

        {/* Mobile Menu */}
        {isMenuOpen && (
          <div className="md:hidden bg-white border-t p-4 flex flex-col gap-4 animate-in fade-in slide-in-from-top-5">
            <a href="#features" onClick={() => setIsMenuOpen(false)}>Fitur</a>
            <a href="#about" onClick={() => setIsMenuOpen(false)}>Tentang</a>
            <button className="bg-blue-600 text-white px-5 py-2.5 rounded-lg w-full">Coba Sekarang</button>
          </div>
        )}
      </nav>

      {/* Hero Section */}
      <section className="pt-32 pb-20 px-4">
        <div className="max-w-7xl mx-auto text-center lg:text-left lg:flex items-center gap-12">
          <div className="lg:w-1/2 space-y-6">
            <div className="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-sm font-semibold border border-blue-100">
              <span className="relative flex h-2 w-2">
                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
              </span>
              Versi 1.0 Kini Tersedia
            </div>
            <h1 className="text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight">
              Kelola Surat <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Lebih Cerdas</span> & Efisien.
            </h1>
            <p className="text-lg text-slate-600 max-w-xl mx-auto lg:mx-0">
              Transformasi digital untuk administrasi persuratan Anda. eSurat13 membantu organisasi mengelola arsip surat masuk dan keluar dengan sistem keamanan tinggi dan aksesibilitas mudah.
            </p>
            <div className="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-4">
              <button className="flex items-center justify-center gap-2 bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-200">
                Mulai Sekarang <ArrowRight className="w-5 h-5" />
              </button>
              <button className="flex items-center justify-center gap-2 bg-white text-slate-700 border border-slate-200 px-8 py-4 rounded-xl font-bold hover:bg-slate-50 transition-all">
                Pelajari Dokumentasi
              </button>
            </div>
          </div>
          
          <div className="lg:w-1/2 mt-16 lg:mt-0 relative">
            <div className="absolute -top-10 -left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div className="absolute -bottom-10 -right-10 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div className="relative bg-white p-2 rounded-3xl shadow-2xl border border-slate-100">
              <div className="bg-slate-900 rounded-2xl overflow-hidden aspect-video flex items-center justify-center text-slate-400 text-sm">
                {/* Simulated UI Content */}
                <div className="w-full h-full p-6 bg-slate-50 flex flex-col gap-4">
                  <div className="flex justify-between items-center border-b pb-4">
                    <div className="h-4 w-32 bg-slate-200 rounded"></div>
                    <div className="h-8 w-8 bg-blue-600 rounded-full"></div>
                  </div>
                  <div className="grid grid-cols-3 gap-4">
                    <div className="h-20 bg-white border rounded-xl p-3 space-y-2">
                      <div className="h-3 w-1/2 bg-slate-100 rounded"></div>
                      <div className="h-6 w-3/4 bg-blue-50 rounded"></div>
                    </div>
                    <div className="h-20 bg-white border rounded-xl p-3 space-y-2">
                      <div className="h-3 w-1/2 bg-slate-100 rounded"></div>
                      <div className="h-6 w-3/4 bg-green-50 rounded"></div>
                    </div>
                    <div className="h-20 bg-white border rounded-xl p-3 space-y-2">
                      <div className="h-3 w-1/2 bg-slate-100 rounded"></div>
                      <div className="h-6 w-3/4 bg-orange-50 rounded"></div>
                    </div>
                  </div>
                  <div className="flex-1 bg-white border rounded-xl p-4 space-y-3">
                    <div className="h-4 w-full bg-slate-50 rounded"></div>
                    <div className="h-4 w-full bg-slate-50 rounded"></div>
                    <div className="h-4 w-3/4 bg-slate-50 rounded"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Feature Section */}
      <section id="features" className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <h2 className="text-blue-600 font-bold tracking-wider uppercase text-sm mb-3">Fitur Utama</h2>
            <p className="text-3xl md:text-4xl font-bold text-slate-900">Segala yang Anda butuhkan untuk kelola surat menyurat.</p>
          </div>
          
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {features.map((feature, idx) => (
              <div key={idx} className="group p-8 rounded-2xl bg-slate-50 border border-transparent hover:border-blue-100 hover:bg-white hover:shadow-xl transition-all duration-300">
                <div className="bg-white w-12 h-12 rounded-xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 transition-transform duration-300">
                  {feature.icon}
                </div>
                <h3 className="text-xl font-bold mb-3 text-slate-800">{feature.title}</h3>
                <p className="text-slate-600 leading-relaxed">
                  {feature.description}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Benefits Section */}
      <section id="about" className="py-24 bg-slate-50 overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="lg:flex items-center gap-16">
            <div className="lg:w-1/2 mb-12 lg:mb-0">
              <div className="relative">
                <div className="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 bg-blue-100 rounded-full opacity-50"></div>
                <div className="relative rounded-3xl overflow-hidden shadow-2xl border-8 border-white">
                  <div className="bg-blue-600 p-12 text-white aspect-square flex flex-col justify-center">
                    <FileText className="w-16 h-16 mb-6 opacity-80" />
                    <h3 className="text-3xl font-bold mb-4">Paperless Office</h3>
                    <p className="text-blue-100 text-lg">
                      Kurangi penggunaan kertas secara signifikan dan beralihlah ke pengarsipan digital yang lebih aman dan mudah dicari.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            
            <div className="lg:w-1/2 space-y-8">
              <h2 className="text-3xl md:text-4xl font-bold text-slate-900">Mengapa Memilih eSurat13?</h2>
              <div className="space-y-6">
                {[
                  "Pengaturan nomor surat otomatis sesuai kategori.",
                  "Penyimpanan berbasis cloud yang aman dan terenkripsi.",
                  "Aksesibilitas dari mana saja menggunakan perangkat apa pun.",
                  "Integrasi mudah dengan workflow kantor yang sudah ada."
                ].map((item, idx) => (
                  <div key={idx} className="flex items-start gap-4">
                    <div className="mt-1 bg-green-100 p-1 rounded-full">
                      <CheckCircle2 className="w-5 h-5 text-green-600" />
                    </div>
                    <p className="text-lg text-slate-700">{item}</p>
                  </div>
                ))}
              </div>
              <div className="pt-4">
                <button className="text-blue-600 font-bold flex items-center gap-2 hover:gap-3 transition-all">
                  Lihat Demo Selengkapnya <ChevronRight className="w-5 h-5" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20">
        <div className="max-w-5xl mx-auto px-4">
          <div className="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-10 md:p-20 text-center text-white shadow-2xl shadow-blue-200 relative overflow-hidden">
            <div className="absolute top-0 left-0 w-full h-full opacity-10">
              <svg className="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 0 L100 0 L100 100 L0 100 Z" fill="url(#grid)" />
                <defs>
                  <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" strokeWidth="0.5" />
                  </pattern>
                </defs>
              </svg>
            </div>
            <div className="relative z-10 space-y-6">
              <h2 className="text-3xl md:text-5xl font-bold">Siap Mendigitalisasi Surat Menyurat Anda?</h2>
              <p className="text-blue-100 text-lg max-w-2xl mx-auto">
                Bergabunglah dengan organisasi modern lainnya yang telah meningkatkan produktivitas administrasi dengan eSurat13.
              </p>
              <div className="pt-6">
                <button className="bg-white text-blue-600 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all shadow-xl">
                  Daftar Sekarang — Gratis
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-slate-900 text-slate-400 py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row justify-between items-center gap-8 border-b border-slate-800 pb-12 mb-12">
            <div className="flex items-center gap-2">
              <div className="bg-blue-600 p-2 rounded-lg">
                <Mail className="w-5 h-5 text-white" />
              </div>
              <span className="text-xl font-bold tracking-tight text-white underline decoration-blue-500 decoration-2 underline-offset-4">eSurat13</span>
            </div>
            <div className="flex gap-8 text-sm">
              <a href="#" className="hover:text-white transition-colors">Syarat & Ketentuan</a>
              <a href="#" className="hover:text-white transition-colors">Kebijakan Privasi</a>
              <a href="https://github.com/ozonerik/eSurat13" target="_blank" className="hover:text-white transition-colors">GitHub Repository</a>
            </div>
          </div>
          <div className="text-center text-sm">
            <p>© {new Date().getFullYear()} eSurat13 Project. Dibuat untuk efisiensi administrasi digital.</p>
          </div>
        </div>
      </footer>

      <style dangerouslySetInnerHTML={{ __html: `
        @keyframes blob {
          0% { transform: translate(0px, 0px) scale(1); }
          33% { transform: translate(30px, -50px) scale(1.1); }
          66% { transform: translate(-20px, 20px) scale(0.9); }
          100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
          animation: blob 7s infinite;
        }
        .animation-delay-2000 {
          animation-delay: 2s;
        }
      `}} />
    </div>
  );
};

export default App;