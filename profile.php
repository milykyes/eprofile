<?php
session_start();
if(!isset($_SESSION["loggedin"])) {
    header("Location: index.php");
    exit;
}

require_once 'data/students.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;

foreach($students as $s) {
    if($s['id'] == $id) {
        $student = $s;
        break;
    }
}

if(!$student) {
    header("Location: listprofile.php");
    exit;
}

function getImagePath($student) {
    // Check if student has a direct image URL
    if (isset($student['image']) && !empty($student['image'])) {
        return $student['image'];
    }

    // Try generating image path based on name
    $imageName = strtolower(str_replace(' ', '', $student['name'])) . '.jpg';
    $possiblePaths = [
        "assets/images/students/$imageName",
        "assets/images/students/" . strtolower($student['name']) . '.jpg',
        "assets/images/students/" . str_replace(' ', '_', strtolower($student['name'])) . '.jpg',
    ];

    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    // Fallback to default avatar
    return "assets/images/default-avatar.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UiTM E-Profile | <?php echo htmlspecialchars($student['name']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --uitm-primary: #6B0F1A;
            --uitm-secondary: rgb(145, 51, 62);
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

        .profile-header {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-secondary));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(107, 15, 26, 0.2);
        }

        .profile-image-container {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
            background: #f5f5f5;
            border-bottom: 3px solid var(--uitm-primary);
        }

        .profile-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .profile-card:hover .profile-image {
            transform: scale(1.1);
        }

        .profile-details {
            padding: 2rem;
        }

        .profile-name {
            color: var(--uitm-primary);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .detail-item {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .detail-item i {
            color: var(--uitm-primary);
            margin-right: 1rem;
            width: 25px;
            text-align: center;
        }

        .profile-tags {
            margin-top: 1.5rem;
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

        .academic-info, .skills-section {
            background: #f1f3f5;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .skill-item {
            margin-bottom: 0.75rem;
        }

        .progress {
            height: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .back-btn {
            background: var(--uitm-primary);
            color: white;
            border: none;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: var(--uitm-secondary);
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
            <a class="navbar-brand" href="listprofile.php">
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

    <div class="profile-header">
        <div class="container text-center">
            <h1>Student Profile</h1>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="profile-card mb-4">
                    <div class="profile-image-container">
                        <?php 
                        $imagePath = getImagePath($student);
                        echo "<img src='" . htmlspecialchars($imagePath) . "' 
                                  class='profile-image' 
                                  alt='" . htmlspecialchars($student['name']) . "'>";
                        ?>
                    </div>
                    <div class="profile-details">
                        <h3 class="profile-name"><?php echo htmlspecialchars($student['name']); ?></h3>
                        
                        <div class="detail-item">
                            <i class="fas fa-id-card"></i>
                            <span><?php echo htmlspecialchars($student['student_id']); ?></span>
                        </div>
                        
                        <div class="detail-item">
                            <i class="fas fa-university"></i>
                            <span><?php echo htmlspecialchars($student['faculty']); ?></span>
                        </div>
                        
                        <div class="profile-tags">
                            <?php foreach($student['tags'] ?? [] as $tag): ?>
                                <span class="profile-tag">
                                    <i class="fas <?php echo htmlspecialchars($tag['icon']); ?> me-1"></i>
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="academic-info">
                    <h4 class="mb-4" style="color: var(--uitm-primary);">
                        <i class="fas fa-graduation-cap me-2"></i>Academic Information
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="detail-item">
                                <i class="fas fa-book"></i>
                                <strong>Program:</strong> 
                                <?php echo htmlspecialchars($student['program']); ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <strong>Email:</strong> 
                                <?php echo htmlspecialchars($student['email']); ?>
                            </div>
                        </div>
                        
                        <?php if(isset($student['gpa'])): ?>
                        <div class="col-md-6 mb-3">
                            <div class="detail-item">
                                <i class="fas fa-chart-line"></i>
                                <strong>GPA:</strong> 
                                <?php echo number_format($student['gpa'], 2); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($student['semester'])): ?>
                        <div class="col-md-6 mb-3">
                            <div class="detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>Current Semester:</strong> 
                                <?php echo htmlspecialchars($student['semester']); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if(isset($student['skills']) && !empty($student['skills'])): ?>
                <div class="skills-section">
                    <h4 class="mb-3" style="color: var(--uitm-primary);">
                        <i class="fas fa-code me-2"></i>Skills
                    </h4>
                    <div class="row">
                        <?php foreach($student['skills'] as $skill): ?>
                            <div class="col-md-6 mb-2">
                                <div class="skill-item">
                                    <strong><?php echo htmlspecialchars($skill['name']); ?></strong>
                                    <div class="progress mt-1">
                                        <div class="progress-bar" 
                                             role="progressbar" 
                                             style="width: <?php echo $skill['level']; ?>%; background-color: var(--uitm-primary);" 
                                             aria-valuenow="<?php echo $skill['level']; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?php echo $skill['level']; ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="listprofile.php" class="btn back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Back to Student List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <p class="mb-0">© <?php echo date('Y'); ?> UiTM Student E-Profile</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>