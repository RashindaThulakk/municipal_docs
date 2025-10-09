function doSearch() {
  const ba = encodeURIComponent(document.getElementById('s_ba_no').value.trim());
  const yr = encodeURIComponent(document.getElementById('s_year').value.trim());
  const ap = encodeURIComponent(document.getElementById('s_applicant_id').value.trim());
  window.location = 'dashboard_ba.php?ba_no=' + ba + '&year=' + yr + '&applicant_id=' + ap;
}
