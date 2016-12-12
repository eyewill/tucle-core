<script>
  $(function(){
    $('#entries').DataTable({
      columnDefs: [
        { className: 'align-middle', targets: '_all' },
        { type: "html", targets: "_all" }
      ],
      select: false
    });

  });
</script>

