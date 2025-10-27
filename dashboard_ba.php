<?php
require 'includes/auth.php';
require_login();
require 'includes/db_connect.php';
$page_title = "Building Application Dashboard";
include 'includes/header.php';
//include 'includes/header_ba.php';

// If editing, load the record to prefill the left form
$edit = null;
if (!empty($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $stmt = $pdo->prepare("SELECT * FROM building_applications WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

// Search values (partial)
$ba_no = $_GET['ba_no'] ?? '';
$year = $_GET['year'] ?? '';
$applicant_id = $_GET['applicant_id'] ?? '';

$ba_search = "%$ba_no%";
$year_search = $year !== '' ? "%$year%" : "%";
$app_search = "%$applicant_id%";

$sql = "SELECT id, ba_no, year, rack_number, applicant_name, applicant_id, assessment_ward, created_at
        FROM building_applications
        WHERE ba_no LIKE ? AND CAST(year AS CHAR) LIKE ? AND applicant_id LIKE ?
        ORDER BY year DESC, ba_no ASC
        LIMIT 200";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ba_search, $year_search, $app_search]);
$rows = $stmt->fetchAll();
?>

<div class="row">
  <!-- LEFT: Add / Edit form -->
  <div class="col-lg-4 col-md-5">
    <div class="left-panel">
      <h5><?php echo $edit ? 'Edit Record' : 'Add New Record'; ?></h5>

      <form method="post" action="add_ba.php" enctype="multipart/form-data" onsubmit="return validateForm()">
        <!-- hidden id when editing -->
        <?php if ($edit): ?>
          <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
        <?php endif; ?>

        <div class="mb-2">
          <label class="form-label">BA No</label>
          <input id="ba_no" name="ba_no" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['ba_no']) : ''; ?>" required>
        </div>

        <div class="mb-2 row">
          <div class="col-6">
            <label class="form-label">Year</label>
            <input id="year" name="year" type="number" min="1900" max="2099" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['year']) : date('Y'); ?>" required>
          </div>
          <div class="col-6">
            <label class="form-label">Rack No</label>
            <input name="rack_number" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['rack_number']) : ''; ?>">
          </div>
        </div>

        <div class="mb-2">
          <label class="form-label">Applicant Name</label>
          <input name="applicant_name" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['applicant_name']): ''; ?>">
        </div>

        <div class="mb-2 row">
          <div class="col-6">
            <label class="form-label">Applicant ID</label>
            <input name="applicant_id" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['applicant_id']): ''; ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Assessment No</label>
            <input name="assessment_no" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['assessment_no']): ''; ?>">
          </div>
        </div>

        <div class="mb-2">
          <label class="form-label">Street Name</label>
          <input name="street_name" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['street_name']): ''; ?>">
        </div>

        <div class="mb-2 row">
          <div class="col-6">
            <label class="form-label">Assessment Ward</label>
            <select name="assessment_ward" class="form-select">
              <option value="">Select</option>
              <?php for ($i=1;$i<=15;$i++): 
                $sel = ($edit && $edit['assessment_ward']==$i) ? 'selected' : '';
              ?>
                <option value="<?php echo $i;?>" <?php echo $sel; ?>><?php echo $i;?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">Date Taken</label>
            <input type="date" name="date_taken" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['date_taken']) : ''; ?>">
          </div>
        </div>

        <div class="mb-2">
          <label class="form-label">Officer Received</label>
          <input name="officer_received" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['officer_received']): ''; ?>">
        </div>

        <div class="mb-2">
          <label class="form-label">Attachment (PDF/JPG/PNG)</label>
          <input id="attachment" type="file" name="attachment" class="form-control" onchange="previewFile(this)">
          <div id="filePreview" class="mt-2">
            <?php if ($edit && $edit['attachment_type']): ?>
              <!-- show link to existing attachment -->
              <a href="download_attachment_ba.php?id=<?php echo $edit['id']; ?>" target="_blank">Existing attachment — view</a>
            <?php endif; ?>
          </div>
        </div>

        <div class="mb-2">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3"><?php echo $edit ? htmlspecialchars($edit['notes']): ''; ?></textarea>
        </div>

        <div class="d-grid gap-2">
          <button class="btn btn-success btn-big" type="submit"><?php echo $edit ? 'Update Record' : 'Add Record'; ?></button>
          <?php if ($edit): ?>
            <a href="dashboard_ba.php" class="btn btn-secondary">Cancel Edit</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- RIGHT: Search and results -->
  <div class="col-lg-8 col-md-7">
    <div class="right-panel">
      <div class="row mb-3">
        <div class="col-md-4">
          <input class="form-control" id="s_ba_no" placeholder="Search BA No (partial allowed)" value="<?php echo htmlspecialchars($ba_no); ?>" onkeyup="searchSuggest()">
        </div>
        <div class="col-md-3">
          <input class="form-control" id="s_year" placeholder="Year" value="<?php echo htmlspecialchars($year); ?>">
        </div>
        <div class="col-md-3">
          <input class="form-control" id="s_applicant_id" placeholder="Applicant ID" value="<?php echo htmlspecialchars($applicant_id); ?>">
        </div>
        <div class="col-md-2">
          <button class="btn btn-warning w-100" onclick="doSearch()">Search</button>
        </div>
      </div>

      <div class="table-wrap">
        <table class="table table-sm table-hover">
          <thead class="table-light">
            <tr>
              <th>BA No</th><th>Year</th><th>Rack</th><th>Applicant</th><th>ID</th><th>Ward</th><th>Attachment</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($rows): foreach ($rows as $r): ?>
              <tr>
                <td><?php echo htmlspecialchars($r['ba_no']); ?></td>
                <td><?php echo htmlspecialchars($r['year']); ?></td>
                <td><?php echo htmlspecialchars($r['rack_number']); ?></td>
                <td><?php echo htmlspecialchars($r['applicant_name']); ?></td>
                <td><?php echo htmlspecialchars($r['applicant_id']); ?></td>
                <td><?php echo htmlspecialchars($r['assessment_ward']); ?></td>
                <td><a href="download_attachment_ba.php?id=<?php echo $r['id']; ?>" target="_blank">View</a></td>
                <td>
                  <a href="dashboard_ba.php?edit_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                  <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Clerk'): ?>
                    <a href="delete_record_ba.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="8">No records found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
