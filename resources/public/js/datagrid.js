Vue.component('vue-grid', {
  template: '#tpl-' + app.tableId,
  replace: true,
  props: {
    title: String,
    data: Array,
    columns: Array,
    actions: Array,
    simple: Boolean,
    current_page: String,
    per_page: String,
    filterKey: String
  },
  data: function () {
    var sortOrders = {}
    this.columns.forEach(function (key) {
      sortOrders[key.field] = 1
    })
    return {
      sortKey: '',
      sortOrders: sortOrders
    }
  },
  computed: {
    filteredData: function () {
      var sortKey = this.sortKey
      var columns = this.columns
      var filterKey = this.filterKey && this.filterKey.toLowerCase()
      var order = this.sortOrders[sortKey] || 1
      var data = this.data

      if (filterKey && vuegrid.filter) {
        data = data.filter(function (row) {
         
          return Object.keys(row).some(function (key) {

              var display = '';

              if( row[key] != null && (Array.isArray(row[key]) || (typeof row[key] === 'object'))){

                column = columns.filter(function (row) {
                  return row.field.indexOf(key) >= 0
                })

                if(column.length !== 0){
                  var field = column[0].field;
                  var style = column[0].style;

                  if (field.indexOf(".") >= 0){  
                    var obj =  field.split('.');

                    if(obj.length == 3){
                      display =  (data[obj[0]][obj[1]] != null) ? data[obj[0]][obj[1]][obj[2]] : '';
                    }else{
                      display =  (data[obj[0]] != null) ? data[obj[0]][obj[1]] : '';
                    }
                  }

                  if (field.indexOf(":") >= 0 && style.indexOf("multi") >= 0){  
                    var obj =  field.split(':');
                    var role = [];
                    
                    row[obj[0]].forEach(function(item,index){
                        role.push(item[obj[1]]); 
                    });

                    display = role.join(", ").toUpperCase();

                  }
                }

              }else{
                
                display = row[key]
              }

              return String(display).toLowerCase().lastIndexOf(filterKey) > -1

          }) 

        })

        vuegrid.pagination.total = data.length;

      }
      if (sortKey) {
        data = data.slice().sort(function (a, b) {

          if (sortKey.indexOf(".") >= 0){  
            var obj =  sortKey.split('.');

            if(obj.length == 3){
              a = a[obj[0]][obj[1]][obj[2]]
              b = b[obj[0]][obj[1]][obj[2]]
            }else{
              a = a[obj[0]][obj[1]]
              b = b[obj[0]][obj[1]]
            }
          }else if (sortKey.indexOf(":") >= 0 ){  
            var obj =  sortKey.split(':');
            var roleA = [];
            var roleB = [];
            
            a[obj[0]].forEach(function(item,index){
                roleA.push(item[obj[1]]); 
            }); 

            b[obj[0]].forEach(function(item,index){
                roleB.push(item[obj[1]]); 
            });

            a = roleA.join(", ").toUpperCase();
            b = roleB.join(", ").toUpperCase();

          }else{
            a = a[sortKey]
            b = b[sortKey]
          }

          return (a === b ? 0 : a > b ? 1 : -1) * order
        })
      }

      return data
    },
    runner : function () {
      var current_page = this.current_page;
      var per_page = this.per_page;
      return (current_page - 1) * per_page;
    }
  },
  filters: {
    capitalize: function (str) {
      return str.charAt(0).toUpperCase() + str.slice(1)
    }
  },
  methods: {
    sortBy: function (key) {
      this.sortKey = key
      this.sortOrders[key] = this.sortOrders[key] * -1
    },
    uriaction : function (uri , id) {
      return uri + '/' +  id;
    },
    checkNumbers : function(amount){
        return accounting.formatNumber(amount);
    },
    createUri : function(data,key){

      var path = this.display(data,key);
      return "{{ handles('/') }}" + path.replace('public','');

    },
    route : function (path) {
      return '/' + path;
    },
    sanitizeUri : function(data,key){

      var path = this.display(data,key);
      return path.substr(path.lastIndexOf('/')+1).substring(0,50).concat(' ...');

    },
    display : function(data,key){

      var field = key.field;
      var style = key.style;
      var display = data[field];

      if (field.indexOf(".") >= 0){  
        var obj =  field.split('.');

        if(obj.length == 3){
          display =  data[obj[0]][obj[1]][obj[2]];
        }else{
          display =  data[obj[0]][obj[1]];
        }
      }

      if (field.indexOf(":") >= 0 && style.indexOf("multi") >= 0){  
        var obj =  field.split(':');
        var role = [];
        
        data[obj[0]].forEach(function(item,index){
            role.push(item[obj[1]]); 
        });

        display = role.join(", ").toUpperCase();

      }

      if(display != undefined && display.length > 100 ){ 
        display = display.substring(0,100).concat(' ...')
      };

      if(key.number) return this.checkNumbers(display);

      return (display != null) ? unescape(display) : display;
    },
    confimAction: function(set,path) { //index is passed by the button
      var self = this;

      if(set.delete){
        
        swal({
              title: app.swalert.confirm.title,
              text: app.swalert.confirm.text,
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: app.swalert.confirm.proceed,
              cancelButtonText: app.swalert.cancel.title,
              reverseButtons: true,
            }).then((result) => {
            if (result.value) {
                axios.get(path)
                .then(function (response) {
                  vuegrid.fetchItems();
                  swal({
                    type: 'success',
                    text: app.swalert.confirm.success,
                    showConfirmButton: false,
                    timer: 1500
                  })
                })
                .catch(function (error) {
                  swal(
                    'Oops...',
                    app.swalert.confirm.failed,
                    'error'
                  )
                });
            } else if (
              // Read more about handling dismissals
              result.dismiss === swal.DismissReason.cancel
            ) {
              swal(
                app.swalert.cancel.title,
                app.swalert.cancel.text,
                'error'
              )
            }
          });
      
      }else{
        location.href = path;
      }
    }
  }
})
var urlParam = new URL(window.location);
var searchParam = new URLSearchParams(urlParam.search);
searchParam.get("search");
var vuegrid = new Vue({
  el: '#' + app.tableId,
  data: {
    timer:'',
    searchQuery: '',
    title: app.title,
    search: app.search,
    filter: app.autoFilter,
    gridColumns: app.column,
    gridData: app.data,
    gridBuilder: app.builder,
    gridApi: app.api,
    gridNew: app.add,
    gridNewDesc: app.addDesc,
    gridActions: app.actions,
    gridActionsSimple: app.simple,
    pagination: {
      total: app.data_total,
      per_page: app.data_per_page,
      from: app.data_from,
      to: app.data_to,
      last_page: app.data_last_page,
      current_page: app.data_current_page
    },
    offset: 4,
  },
  computed : {
    isActived: function () {
            return this.pagination.current_page;
        },
    pagesNumber: function () {
        if (!this.pagination.to) {
            return [];
        }
        var from = this.pagination.current_page - this.offset;
        if (from < 1) {
            from = 1;
        }

        var to = from + (this.offset * 2);

        if (to >= this.pagination.last_page) {
            to = this.pagination.last_page;
        }

        var pagesArray = [];

        while (from <= to) {
            pagesArray.push(from);
            from++;
        }
        return pagesArray;
    }
  },
  mounted: function () {
    // this.fetchItems(this.pagination.current_page);
    this.timer = setInterval(function () { 
      this.fetchItems(this.pagination.current_page);
      }.bind(this), 60000)
  },
  methods: {
      fetchItems: function (page) {
          var data = {page: page};
          axios.get(this.gridApi + '?page=' + page + '&search=' + this.searchQuery)
            .then(function (response) {

            vuegrid.gridData = response.data.data;
            vuegrid.pagination.current_page = (response.data.current_page > response.data.last_page ) ? 1 : response.data.current_page;

            vuegrid.pagination.total = response.data.total;
            vuegrid.pagination.last_page = response.data.last_page;
            vuegrid.pagination.to = response.data.last_page;

          }).catch(function (error) {
            console.log(error);
              swal(
                'Oops...',
                app.swalert.confirm.failed,
                'error'
              )
          });
      },
      changePage: function (page) {
          this.pagination.current_page = page;
          this.fetchItems(page);
      }
  },
  beforeDestroy() {
    clearIntervall(this.timer)
  }
})
//# sourceMappingURL=datagrid.js.map
