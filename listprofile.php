<?php 
session_start(); 
if(!isset($_SESSION["loggedin"])) {     
    header("Location: login.php");
    exit; 
}  

require_once 'data/students.php';  

function getImagePath($studentName) {
    $imageName = strtolower(str_replace(' ', '', $studentName)) . '.jpg';
    return file_exists("assets/images/students/$imageName") 
           ? "assets/images/students/$imageName" 
           : "assets/images/default-avatar.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM E-Profile | Student Profiles</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --uitm-primary: #6B0F1A;
            --uitm-secondary:rgb(145, 51, 62);
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: var(--uitm-primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .page-header {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            height: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(107, 15, 26, 0.2);
        }

        .profile-card:hover .profile-image {
            transform: scale(1.1);
        }

        .profile-card:hover .profile-info {
            background: rgba(107, 15, 26, 0.9);
        }

        .profile-card:hover .btn-view {
            background: var(--uitm-secondary);
            padding: 0.6rem 1.2rem;
        }

        .profile-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--uitm-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .profile-card:hover::after {
            transform: scaleX(1);
        }

        .profile-image-wrap {
            position: relative;
            padding-top: 75%;
            overflow: hidden;
            background: #f5f5f5;
        }

        .profile-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: rgba(0,0,0,0.7);
            color: white;
        }

        .profile-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .profile-faculty {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .card-content {
            padding: 1rem;
        }

        .profile-detail {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .profile-tag {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            margin: 0.25rem;
            border-radius: 15px;
            background: rgba(107, 15, 26, 0.1);
            color: var(--uitm-primary);
            font-size: 0.8rem;
        }

        .btn-view {
            background: var(--uitm-primary);
            color: white;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: background 0.3s;
            display: block;
            text-align: center;
            margin-top: 1rem;
        }

        .btn-view:hover {
            background: var(--uitm-secondary);
            color: white;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--uitm-primary);
        }

        .filter-section {
            margin-bottom: 2rem;
        }

        .filter-btn {
            border: none;
            background: white;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border-radius: 20px;
            color: #666;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: var(--uitm-primary);
            color: white;
        }

        footer {
            background: #333;
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
        }
        /* Responsive Design */
@media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .footer-bottom {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .footer-links {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .copyright {
        text-align: center;
    }
    
    .geometric-lines {
        display: none;
    }
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>UiTM E-Profile
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1 class="text-center mb-4">Student Profiles</h1>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Search students...">
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="stats-card">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-number"><?php echo count($students); ?></div>
                    <div>Total Students</div>
                </div>
                <div class="col-md-3">
                    <div class="stat-number"><?php echo count(array_unique(array_column($students, 'faculty'))); ?></div>
                    <div>Faculties</div>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">
                        <?php echo count(array_filter($students, fn($s) => $s['deans_list'] ?? false)); ?>
                    </div>
                    <div>Dean's List</div>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">89%</div>
                    <div>Active Students</div>
                </div>
            </div>
        </div>

        <div class="filter-section text-center">
            <button class="filter-btn active">All</button>
            <?php foreach(array_unique(array_column($students, 'faculty')) as $faculty): ?>
                <button class="filter-btn"><?php echo htmlspecialchars($faculty); ?></button>
            <?php endforeach; ?>
        </div>

        <div class="row g-4">
            <?php foreach($students as $student): ?>
            <div class="col-lg-4 col-md-6">
                <div class="profile-card">
                    <div class="profile-image-wrap">
                        <img src="<?php echo htmlspecialchars($student['image']); ?>" 
                             class="profile-image" 
                             alt="<?php echo htmlspecialchars($student['name']); ?>">
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo htmlspecialchars($student['name']); ?></h5>
                            <div class="profile-faculty"><?php echo htmlspecialchars($student['faculty']); ?></div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="profile-detail">
                            <i class="fas fa-id-card me-2"></i>
                            <?php echo htmlspecialchars($student['student_id']); ?>
                        </div>
                        <div class="profile-detail">
                            <i class="fas fa-university me-2"></i>
                            <?php echo htmlspecialchars($student['faculty']); ?>
                        </div>
                        <div class="mt-2">
                            <?php if($student['deans_list'] ?? false): ?>
                                <span class="profile-tag">
                                    <i class="fas fa-star me-1"></i>Dean's List
                                </span>
                            <?php endif; ?>
                            <?php foreach($student['tags'] ?? [] as $tag): ?>
                                <span class="profile-tag">
                                    <i class="fas <?php echo htmlspecialchars($tag['icon']); ?> me-1"></i>
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <a href="profile.php?id=<?php echo urlencode($student['id']); ?>" class="btn-view">
                            View Profile <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <p class="mb-0">© <?php echo date('Y'); ?> UiTM Student E-Profile</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/student-filter.js"></script>
</body>
</html>