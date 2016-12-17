<script>
  $(function(){
    var factory = $.extend({}, DataTablesFactory, {
      options: {
        columnDefs: [
          { className: 'align-middle', targets: '_all' },
          { type: "html", targets: "_all" }
        ],
        select: false
      }
    });
    factory.make();
  });
</script>
