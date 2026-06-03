@extends('frontend.layouts.app')
@push('stylepush')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>

       
  body{
    font-family:"Poppins", sans-serif;
    background-color:#f1f4f6;
          }

/* Title with Breadcrumb */

.about-us {
    background-color: #F5F3F4;
    height: 30vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.about-us h1 {
    font-size: 64px;
    font-weight: 700;
}


.breadcrumb li a {
    color: black;
    font-size: 16px;
    font-weight: 500;
    line-height: 28px;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: black;
    font-size: 18px;
    font-weight: 700;
    line-height: 28px;
    text-decoration: none;
}

.year {
    padding: 32px;
    background-color: #3B8DED;
    border-radius: 5px;
}

.year h1 {
    font-size: 44px;
    line-height: 54px;
    color: white;
    font-weight: 700;
}

.year p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 18px;
    line-height: 28px;
    font-weight: 500;
}

.about-sub-heading {
    font-size: 16px;
    font-weight: 500;
    line-height: 16px;
    background-color: #FFEAD7;
}



/* About Us  */

.highlight-box {
    background-color: #3B8DED;
    color: white;
    padding: 25px;
    font-size: 24px;
    font-weight: bold;
    text-align: start;
}


.experience-box {
    background-color: #0a0a23;
    color: white;
    padding: 25px;
    font-size: 24px;
    font-weight: bold;
    text-align: start;
}

.icon-circle {
    background-color: #d0e6ff;
    border-radius: 50%;
    padding: 15px;
    display: inline-block;
    margin-right: 15px;
    /* justify-content: center;
    display: flex;
    align-items: center;
    width: 100px; */
}

.about-btn {
    background-color: #3B8DED;
    border: none;
    color: white;
    font-size: 16px;
    font-weight: 700;
}




.about-btn:hover {
    background-color: #000000;
    color: #ffff;
    transition: all 0.5s;
}

.bg-orange {
    background-color: #d0e6ff;
    font-size: 16px;
    font-weight: 500;
}

.about-us-left h2 {
    font-size: 44px;
    font-weight: 700;
}

.about-us-left p {
    font-size: 18px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
}

.about-us-right h2 {
    font-size: 44px;
}

.about-us-right p {
    font-size: 18px;
}

.about-us-right h6 {
    font-size: 20px;
}


/* Mission Section  */

.mission {
    background-color: #F5F3F4;
}

.mission-tag {
    background-color: #d0e6ff;
    color: #000;
    font-weight: 500;
    display: inline-block;
    padding: 5px 15px;
    border-radius: 6px;
    margin-bottom: 10px;
}

.mission-title {
    font-weight: 700;
    color: rgb(8, 17, 32);
    font-size: 44px;
    line-height: 54px;
}

.mission-desc {
    color: rgb(119, 120, 123);
    font-size: 18px;
    font-weight: 500;
    line-height: 28px
}

.card-box {
    border: none;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
    border-radius: 12px;
    padding: 30px 25px;
    height: 100%;
}

.card-icon {
    background-color: #d0e6ff;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.card-icon i {
    font-size: 24px;
    color: #fca351;
}

.card-title {
    font-weight: 700;
    font-size: 20px;
    color: #0d0d0d;
    line-height: 20px;
}

.card-text {
    color: #77787B;
    font-size: 18px;
    font-weight: 500;
    line-height: 28px;
}



/* Why choose us  */

.info-box {
    background-color: #fff;
    text-align: center;
    border-radius: 4px;
    padding: 24px;
    text-align: center;
    transition: all 0.4s;
    margin-bottom: 30px;
    margin-top: 20px;
}

.info-box h4 {
    font-size: 32px;
    font-weight: 700;
    line-height: 32px;
}

.info-box p {
    font-size: 17px;
    font-weight: 500;
    line-height: 30px;
}

.info-box i {
    font-size: 30px;
    margin-bottom: 10px;
    display: block;
}

.tag-label {
    background-color: #d0e6ff;
    color: #000;
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 5px;
    display: inline-block;
    margin-bottom: 15px;
}

.btn-orange {
    background-color: #ff6a00;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
}

.btn-orange:hover {
    background-color: #e55d00;
}

.img-fluidd {
    max-width: 93%;
    height: auto;
}

.img-fluid {
    min-width: 100%;
    height: auto;
}

.why-chooses-us-right h2 {
    font-size: 44px;
    font-weight: 700;
    line-height: 54px;
}

.why-chooses-us-right p {
    font-size: 18px;
    font-weight: 500;
    line-height: 28px;
}


/* Testimonial Section  */

.testimonial {
    background-color: #F5F3F4;
}

.testimonial h2 {
    font-size: 44px;
    font-weight: 700;
    line-height: 54px;
}

.testimonial p {
    font-size: 18px;
    font-weight: 500;
    line-height: 28px;
}

.space30 {
    height: 30px;
}

.testimonial-badge {
    background-color: #d0e6ff;
    color: #000;
    font-size: 14px;
    padding: 4px 12px;
    border-radius: 5px;
    display: inline-block;
}

.testimonial-card {
    background-color: white;
    border-radius: 5px;
    padding: 35px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.testimonial-card p {
    font-size: 20px;
    font-weight: 500;
    line-height: 30px;
}

.testimonial-card h6 {
    font-size: 24px;
    font-weight: 700;
    line-height: 24px;
}

.testimonial-card small {
    font-size: 16px;
}

.testimonial-stars i {
    color: #3B8DED;
    margin-right: 4px;
}

.testimonial-img {
    width: 160px;
    height: auto;
    border-radius: 5px;
}

.arrow-btn {
    background-color: #d0e6ff;
    border: none;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 18px;
}


/* Founder  */

.icons {
    /* opacity: 0; */
    transition: all 0.4s;
}

 ul {
    padding-left: 0rem;
}

.icons ul {
    margin-top: 20px;
}

.icons ul li {
    display: inline-block;
}

.icons ul li a {
    display: inline-block;
    height: 40px;
    width: 40px;
    line-height: 40px;
    text-align: center;
    border-radius: 50%;
    background-color: #d0e6ff;
    margin: 0px 3px;
    color: var(--vtc-text-heading-text-1);
    transition: all 0.4s;
}



/* Small devices (landscape phones, 576px and up) */
@media (max-width: 576px) {
    .about-us-right h2 {
        font-size: 20px;
    }

    .about-us-right p {
        font-size: 14px;
    }

    .about-us-right h6 {
        font-size: 16px;
    }

    .mission-title {
        font-weight: 600;
        color: rgb(8, 17, 32);
        font-size: 17px;
        line-height: 34px;
    }

    .mission-desc {
        color: rgb(119, 120, 123);
        font-size: 16px;
        font-weight: 500;
        line-height: 28px;
    }

    .why-chooses-us-right h2 {
        font-size: 20px;
        font-weight: 700;
        line-height: normal;
    }

    .why-chooses-us-right p {
        font-size: 16px;
        font-weight: 500;
        line-height: normal;
    }

    .testimonial h2 {
        font-size: 18px;
        font-weight: 700;
        line-height: normal;
    }

    .testimonial p {
    font-size: 16px;
    font-weight: 500;
    line-height: normal;
}
}

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) {
    .about-us-right h2 {
        font-size: 25px;
    }

    .about-us-right p {
        font-size: 16px;
    }

    .about-us-right h6 {
        font-size: 20px;
    }

    .mission-title {
        font-weight: 600;
        color: rgb(8, 17, 32);
        font-size: 17px;
        line-height: 54px;
    }

    .mission-desc {
        color: rgb(119, 120, 123);
        font-size: 17px;
        font-weight: 500;
        line-height: 28px;
    }

    .why-chooses-us-right h2 {
        font-size: 20px;
        font-weight: 700;
        line-height: normal;
    }

    .why-chooses-us-right p {
        font-size: 14px;
        font-weight: 500;
        line-height: normal;
    }

    .testimonial h2 {
        font-size: 20px;
        font-weight: 700;
        line-height: normal;
    }

    .testimonial p {
    font-size: 18px;
    font-weight: 500;
    line-height: normal;
}
}

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) {
    .about-us-right h2 {
        font-size: 30px;
    }

    .about-us-right p {
        font-size: 18px;
    }

    .mission-title {
        font-weight: 700;
        color: rgb(8, 17, 32);
        font-size: 44px;
        line-height: 54px;
    }

    .mission-desc {
        color: rgb(119, 120, 123);
        font-size: 18px;
        font-weight: 500;
        line-height: 28px;
    }

    .why-chooses-us-right h2 {
        font-size: 44px;
        font-weight: 700;
        line-height: 54px;
    }

    .why-chooses-us-right p {
        font-size: 18px;
        font-weight: 500;
        line-height: 28px;
    }

    .testimonial h2 {
        font-size: 44px;
        font-weight: 700;
        line-height: 54px;
    }
}

/* X-Large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) {
    .about-us-right h2 {
        font-size: 44px;
    }


}

/* XX-Large devices (larger desktops, 1400px and up) */
@media (min-width: 1400px) {
    .about-us-right h2 {
        font-size: 44px;
    }
}
 </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
<div class="breadcrumb-banner">
    <div class="breadcrumb-banner-img">
        <img src="{{show_image(file_path()['frontend']['path'].'/'.@frontend_section_data($breadcrumb->value,'image'),@frontend_section_data($breadcrumb->value,'image','size'))}}" alt="breadcrumb.jpg">
    </div>
    <div class="page-Breadcrumb">
        <div class="Container">
            <div class="breadcrumb-container">
                <h1 class="breadcrumb-title">{{($title)}}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">
                            {{translate('home')}}
                        </a></li>

                        <li class="breadcrumb-item active" aria-current="page">
                            {{translate($title)}}
                        </li>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
    
    <div class="container-fluid about py-3 py-lg-5">
        <div class="container py-lg-5 about-process">
            <div class="row align-items-center justify-content-center justify-content-lg-evenly">
                <!-- Left Column -->
                <div class="col-12 col-lg-6 about-us-left">
                    <div class="row gy-lg-5">
                        <div class="col-12 col-lg-6">
                            <img src="/assets/images/about/about3-img1.webp"
                                class="img-fluid rounded mb-3" alt="Meeting" />
                            <div class="experience-box rounded">
                                <h2 class="text-white"> 1590+ </h2><small class="d-block mt-2">
                                    <p> Years of
                                        Experienced </p>
                                </small>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="highlight-box rounded mb-2">
                                <h2> 16+ </h2><small class="d-block mt-2">
                                    <p> Years of
                                        Experienced </p>
                                </small>
                            </div>
                            <img src="/assets/images/about/about3-img2.webp"
                                class="img-fluid rounded mt-2" alt="Woman" />
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-12 col-lg-6 about-us-right">
                    <div class="right-mr ms-lg-4 mt-4 mt-lg-0">
                        <span class="badge text-dark mb-2 bg-orange">About Us</span>
                        <h2 class="fw-bold mb-3">Welcome to Aeritx – Your Trusted Printing Partner</h2>
                        <p class="text-muted mb-4">
                            At Aeritx, we believe printing should be simple, efficient, and stress-free. We’re here to
                            help individuals, offices, and businesses find the right printing solutions — without the
                            confusion of endless choices and tech jargon. Whether you're buying your first printer or
                            managing bulk servicing, we bring expert advice and dependable support, every step of the
                            way.
                        </p>
                        <div class="mb-4 d-flex justify-content-center align-items-start align-items-lg-center">
                            <div class="icon-circle">
                                <img src="/assets/images/about/about3-icon1.webp"
                                    width="85" alt="Career Icon" />
                            </div>
                            <div>
                                <h6 class="fw-bold mb-3">Our Mission</h6>
                                <p class="text-muted mb-0">To simplify printing for everyone — from everyday users to
                                    corporate clients — by offering expert support, cost-effective products, and
                                    seamless service experiences.</p>
                            </div>
                        </div>
                        <div class="mb-4 d-flex justify-content-center align-items-start align-items-lg-center">
                            <div class="icon-circle">
                                <img src="/assets/images/about/about3-icon2.webp"
                                    width="60" alt="Leader Icon" />
                            </div>
                            <div>
                                <h6 class="fw-bold mb-3">Our Vision</h6>
                                <p class="text-muted mb-0">To become India’s most reliable name in printing solutions,
                                    built on a foundation of trust, innovation, and customer satisfaction.</p>
                            </div>
                        </div>
                        <a href="https://aeritx.com/contact" class="btn about-btn px-3 py-2 px-lg-4 py-lg-3 mt-3">Contact Us <i
                                class="fa-solid fa-arrow-right-long  m-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mission py-3 py-lg-5">
        <div class="container py-3 py-lg-5 text-lg-center">
            <h2 class="mission-title display-6">What We Offer</h2>
            <p class="mission-desc mb-3 mt-2 mb-lg-5 mt-lg-4">
                We believe that by connecting talented professionals with leading companies,<br />
                we can create opportunities for growth, innovation
            </p>

            <div class="row g-4 text-start">
                <div class="col-md-4">
                    <div class="card-box">
                        <div class="card-icon"><img
                                src="/assets/images/about/mission-icon1.webp"
                                alt="mission-icon"></div>
                        <div class="card-title">Printer Sales & Setup </div>
                        <p class="card-text mt-3">
                            Get the best options matched to your exact needs
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box">
                        <div class="card-icon"><img
                                src="/assets/images/about/mission-icon2.webp"
                                alt="mission-icon"></div>
                        <div class="card-title">Printer Accessories </div>
                        <p class="card-text mt-3">
                            A complete range of cartridges, toners, and more
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box">
                        <div class="card-icon"><img
                                src="/assets/images/about/mission-icon1.webp"
                                alt="mission-icon"></div>
                        <div class="card-title">Expert Consultation </div>
                        <p class="card-text mt-3">
                            One-on-one help to make the right buying decision
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 py-lg-5 why-chooses-us">
        <section class="py-3 py-lg-5">
            <div class="container">
                <div class="row align-items-center">

                    <!-- Left Column - Info Boxes -->
                    <div class="col-lg-2 mb-4 mb-lg-0 why-chooses-us-left">
                        <div class=" px-2">
                            <div class="info-box">
                                <img src="/assets/images/about/choose1-icon1.webp"
                                    alt="why-chooses-us-icon">
                                <h4 class="fw-bold my-2">98%</h4>
                                <p class="mb-0">Successful Client</p>
                            </div>
                            <div class="info-box">
                                <img src="/assets/images/about/choose1-icon2.webp"
                                    alt="why-chooses-us-icon">
                                <h4 class="fw-bold my-2">79%</h4>
                                <p class="mb-0">Team Advisors</p>
                            </div>
                            <div class="info-box">
                                <img src="/assets/images/about/choose1-icon3.webp"
                                    alt="why-chooses-us-icon">
                                <h4 class="fw-bold my-2">12+</h4>
                                <p class="mb-0">Years Experience</p>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Column - Image -->
                    <div class="col-lg-5 px-2 why-chooses-us-middle">
                        <div class=" mb-4 mb-lg-0 d-flex justify-content-center justify-content-lg-start">
                            <img src="/assets/images/about/choose1-img.webp"
                                alt="Team Meeting" class="img-fluidd rounded">
                        </div>
                    </div>


                    <!-- Right Column - Content -->
                    <div class="col-lg-5 why-chooses-us-right">
                        <span class="tag-label">Why Choose Us</span>
                        <h2 class="fw-bold mb-3">Why Choose Aeritx ?</h2>
                        <p class="text-muted">
                            Our team of industry experts is dedicated to understanding your unique needs and delivering
                            tailored solutions that propel your business forward.
                        </p>
                        <div class="row">
                            <div class="col-12">
                                <p><i class="fa-solid fa-circle-check"></i> Honest, unbiased product recommendations</p>
                                <p><i class="fa-solid fa-circle-check"></i> Support from experienced professionals</p>
                                <p><i class="fa-solid fa-circle-check"></i> Competitive prices with quality assurance
                                </p>
                                <p><i class="fa-solid fa-circle-check"></i> Quick response & after-sales service</p>
                                <p><i class="fa-solid fa-circle-check"></i> Eco-friendly and sustainable options
                                    availablee</p>
                            </div>
                            <div class="col-12 col-lg-6">

                            </div>
                        </div>
                        <a href="https://aeritx.com/contact" class="btn about-btn px-3 py-2 px-lg-4 py-lg-3 mt-3">Contact Us <i
                                class="fa-solid fa-arrow-right-long  m-2"></i></a>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <div class="container-fluid py-3 py-lg-5 why-chooses-us">
        <section class="py-3 py-lg-5">
            <div class="container">
                <div class="row align-items-center">


                    <!-- Middle Column - Image -->
                    <div class="col-lg-5 px-2 why-chooses-us-middle">
                        <div class=" mb-4 mb-lg-0 d-flex justify-content-center">
                            <img src="/assets/images/about/team1-img2.webp"
                                alt="Team Meeting" class="img-fluidd rounded">
                        </div>
                    </div>


                    <!-- Right Column - Content -->
                    <div class="col-lg-6 why-chooses-us-right">
                        <span class="tag-label">Founder </span>
                        <h2 class="fw-bold mb-3">Meet the Founder – <br> Priyanshu Jain</h2>
                        <p class="text-muted">
                            Driven by a passion for tech and a commitment to helping people, Priyanshu Jain started
                            Aeritx to offer more than just products — he wanted to create peace of mind. With an eye for
                            detail and a heart for service, Priyanshu ensures that every customer receives not only the
                            right product but also the right guidance.
                        </p>
                        <p class="text-muted">
                            At Aeritx, it’s not just about selling — it’s about solving problems and building trust.
                        </p>

                        <!-- <a href="#" class="btn about-btn px-3 py-2 px-lg-4 py-lg-3 mt-3">About US <i
                                class="fa-solid fa-arrow-right-long m-2"></i></a> -->

                        <div class="icons">
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    @endsection
@push('scriptpush')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
        
        @endpush
