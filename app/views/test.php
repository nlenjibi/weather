<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Environmental Sensor Tester | AhmadLogs</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .test-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,123,255,0.3);
        }

        .title-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .title-section h2 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .title-section p {
            color: #6c757d;
        }
    </style>
</head>
<body>
<?php
    if (session('success')) {
      ?>
      <div class="alert alert-success alert-dismissible fade show" style="background-color: rgb(58, 151, 0); color: aliceblue;" role="alert">
        <strong>Success!</strong> <?= session('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
      unset($_SESSION['success']);
    }

    if (session('error')) {
      ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?= session('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
      unset($_SESSION['error']);
    }
    ?>
    <div class="container">
        <div class="test-container">
            <div class="title-section">
                <h2><i class="fas fa-cloud-sun me-2"></i>Sensor Data Tester</h2>
                <p class="lead">Validate environmental sensor readings</p>
            </div>

            <form method="POST" action="<?= htmlspecialchars(POST_DATA_URL) ?>">
                <div class="mb-4">
                    <label for="api_key" class="form-label">API Key</label>
                    <input type="text" 
                           class="form-control form-control-lg" 
                           id="api_key" 
                           name="api_key" 
                           value="<?= htmlspecialchars(PROJECT_API_KEY) ?>"
                           required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="temperature" class="form-label">
                            Temperature (°C)
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="temperature" 
                                   name="temperature" 
                                   step="0.01"
                                   value="16.53" 
                                   required
                                   pattern="^-?\d+(?:\.\d+)?$">
                            <span class="input-group-text">°C</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="humidity" class="form-label">
                            Humidity (%)
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control" 
                                   id="humidity" 
                                   name="humidity" 
                                   step="0.01"
                                   value="55.67"
                                   required
                                   pattern="^\d+(?:\.\d+)?$">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary submit-btn">
                    <i class="fas fa-paper-plane me-2"></i>Submit Test Data
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>