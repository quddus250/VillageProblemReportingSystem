<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="hero">
    <div class="container">
        <h1>Report Village Problems Easily</h1>
        <p>A simple online platform for villagers to report local issues and track complaint status.</p>
        <div style="margin-top: 2rem;">
            <a href="report_problem.php" class="btn btn-accent" style="margin-right: 1rem;">Report a Problem</a>
            <a href="track_complaint.php" class="btn btn-outline" style="color: white !important; border-color: white;">Track Complaint</a>
        </div>
    </div>
</div>

<div class="container" style="padding: 4rem 0;">
    <h2 style="text-align: center; margin-bottom: 2rem;">Common Problem Categories</h2>
    <div class="grid-container">
        <div class="card">
            <h3>🛣️ Road Damage</h3>
            <p>Report potholes, broken roads, or unfinished construction.</p>
        </div>
        <div class="card">
            <h3>🌊 Drainage Issue</h3>
            <p>Report blocked or overflowing drains in your area.</p>
        </div>
        <div class="card">
            <h3>🗑️ Garbage Problem</h3>
            <p>Report uncollected garbage or illegal dumping.</p>
        </div>
        <div class="card">
            <h3>💡 Street Light Issue</h3>
            <p>Report broken or non-functioning street lights.</p>
        </div>
        <div class="card">
            <h3>💧 Water Supply</h3>
            <p>Report water shortages or pipe leakages.</p>
        </div>
        <div class="card">
            <h3>⚡ Electricity Issue</h3>
            <p>Report power cuts, broken poles, or dangling wires.</p>
        </div>
        <div class="card">
            <h3>🏥 Health & Sanitation</h3>
            <p>Report public health hazards or sanitation issues.</p>
        </div>
        <div class="card">
            <h3>📝 Other Issues</h3>
            <p>Report any other civic issues not listed above.</p>
        </div>
    </div>
</div>

<div style="background-color: var(--secondary); padding: 4rem 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 2rem;">How It Works</h2>
        <div class="grid-container" style="gap: 2rem;">
            <div style="text-align: center; padding: 2rem; background: var(--white); border-radius: 12px;">
                <h1 style="color: var(--accent); font-size: 3rem; margin-bottom: 1rem;">1</h1>
                <h3>Submit Complaint</h3>
                <p>Register or login to your account and submit a detailed report of the problem.</p>
            </div>
            <div style="text-align: center; padding: 2rem; background: var(--white); border-radius: 12px;">
                <h1 style="color: var(--accent); font-size: 3rem; margin-bottom: 1rem;">2</h1>
                <h3>Admin Reviews</h3>
                <p>Local authorities will review your complaint and assign priority.</p>
            </div>
            <div style="text-align: center; padding: 2rem; background: var(--white); border-radius: 12px;">
                <h1 style="color: var(--accent); font-size: 3rem; margin-bottom: 1rem;">3</h1>
                <h3>Status Updated</h3>
                <p>Track the progress in real-time as the problem gets resolved.</p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding: 4rem 0; text-align: center;">
    <h2>Benefits of the System</h2>
    <p style="max-width: 800px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem; line-height: 1.8;">
        Easy registration, faster tracking of issues, transparent communication with local authorities, saves your valuable time, and ultimately helps in the rapid development of our village.
    </p>
</div>

<?php include 'includes/footer.php'; ?>
