<div class="row  py-3 bg-white text-dark">
  <div class="col-md-12" id="griddata">
    <H2 v-if="title.length > 0" class="text-primary">@{{title}}</H2>
    <div v-if="search" class="row my-2 bg-white text-dark">
      <div class="col-md-12">
        <form class="form" id="search">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend">
                    <i class="fas fa-search"></i>
                  </span>
                </div>
                <input type="text" class="form-control col-md-10 form-control-sm" name="search" v-model="searchQuery" placeholder="{{ trans('joesama/vuegrid::datagrid.search') }}" >
                <span class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" @click.prevent="fetchItems(1)">
                  {{ trans('joesama/vuegrid::datagrid.search') }}
                  </button>
                </span>
              </div><!-- /input-group -->
            </div>
            <div class="col-md-8 text-right" v-if="gridNew || gridExtraButtons.length > 0">
              <a v-for="button in gridExtraButtons" class="btn btn-sm btn-primary" :href="button.uri">
                <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;
                @{{ button.desc }}
              </a>
              <a class="btn btn-sm btn-primary" :href="gridNew" v:if="gridNew">
              <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;
                @{{ gridNewDesc }}
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
        <div class="col-md-12">
          <demo-grid
            :data="gridData"
            :title="title"
            :actions = "gridActions"
            :simple = "gridActionsSimple"
            :columns="gridColumns"
            :current_page="pagination.current_page"
            :per_page="pagination.per_page"
            :filter-key="searchQuery">
          </demo-grid>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-2 text-left">
          <ul class="pagination pagination-sm">
            <li>
             <small>
              <strong>{{ trans('joesama/vuegrid::datagrid.total') }}</strong>
              &nbsp;:&nbsp;@{{ pagination.total }}
              </small>
            </li>
          </ul>
          </div>
          <div class="col-md-8">
          <ul class="pagination pagination-sm justify-content-center">
              <li class="page-item" v-if="pagination.current_page > 1">
                  <a class="page-link" href="#" aria-label="Previous"
                     @click.prevent="changePage(pagination.current_page - 1)">
                      <span ria-hidden="true">&laquo;</span>
                  </a>
              </li>
              <li v-for="page in pagesNumber"
                  v-bind:class="[ page == isActived ? 'page-item active' : '']">
                  <a class="page-link" href="#"
                     @click.prevent="changePage(page)">@{{ page }}</a>
              </li>
              <li class="page-item"  v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link" href="#" aria-label="Next"
                     @click.prevent="changePage(pagination.current_page + 1)">
                      <span aria-hidden="true">&raquo;</span>
                  </a>
              </li>
          </ul>
          </div>
          <div class="col-md-2 text-right">
            <div class="btn-group" role="group" aria-label="...">
              <a  class="btn btn-sm btn-default">
              <i class="fas fa-file-pdf" aria-hidden="true"></i>
              </a>
              <a class="btn btn-sm btn-default">
              <i class="fas fa-file-excel" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/x-template" id="grid-template">
<div class="table-responsive-md">
  <table  id="datagrid" class="table table-bordered table-condensed table-striped table-hover table-sm">
    <thead class="thead-dark">
      <tr>
        <th class="text-center" width="20px">#</th>
        <th v-for="key in columns"
          @click="sortBy(key.field)"
          :class="{ active: sortKey == key.field }">
          @{{ key.title | capitalize }}
          <span class="arrow" :class="sortOrders[key.field] > 0 ? 'asc' : 'dsc'">
          </span>
        </th>
        <th v-if="actions" width="150px"  class="text-center">{{ trans('joesama/vuegrid::datagrid.actions') }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-if="filteredData.length < 1">
        <td :colspan="columns.length + 2"><p><center>{{ trans('joesama/vuegrid::datagrid.empty') }}</center></p></td>
      </tr>
      <tr v-for="(entry, index) in filteredData">

        <td class="text-center bg-white text-dark" >@{{ runner + (index + 1 ) }}</td>
        <td v-for="key in columns" v-bind:class="[ key.style ? key.style : '']">
          <a v-if="key.file" class="btn btn-primary btn-xs" :href="createUri(entry,key)" target="_blank">
            <i class="fa fa-download" aria-hidden="true"></i>&nbsp;@{{ sanitizeUri(entry,key) }}
          </a>
          <a v-if="key.uri" :href="uriaction(key.uri.url,entry[key.uri.key])" target="_blank">@{{ display(entry,key) }}</a>
          <a v-if="key.route" :href="route(display(entry,key))" target="_blank">@{{ display(entry,key) }}</a>
          <span v-if="key.iconic">
              <i v-if="display(entry,key) == 1" class="fa fa-check-circle-o fa-2x text-success" aria-hidden="true"></i>
              <i v-if="display(entry,key) == 0" class="fa fa-times fa-2x text-danger" aria-hidden="true"></i>
          </span>
          <span v-if="!key.file && !key.uri && !key.route && !key.iconic">@{{ display(entry,key) }}</span>
        </td>
        <td v-if="actions" class="text-center bg-white text-dark" >
          <!-- START BUTTON IS NOT SIMPLE -->
          <div class="btn-group" v-if="(actions.length > 1) && !simple">
            <button type="button" class="btn btn-outline-primary btn-sm font-weight-light" data-toggle="button" aria-pressed="false" autocomplete="off">{{ trans('joesama/vuegrid::datagrid.actions') }} </button>
            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li v-for="btn in actions">
              <a :href="uriaction(btn.url,entry[btn.key])">
              <i v-bind:class="btn.icons" aria-hidden="true"></i>&nbsp;@{{ btn.action }} @{{ btn.delete }}
              </a>
              </li>
            </ul>
          </div>
          <a v-if="(actions.length < 2) && !simple" :href="uriaction(btn.url,entry[btn.key])" v-for="btn in actions" class="btn btn-sm btn-actions">
          <i v-bind:class="btn.icons" aria-hidden="true"></i>&nbsp;
          @{{ btn.action }}
          </a>
          <!-- END BUTTON IS NOT SIMPLE -->

          <!-- START BUTTON IS SIMPLE -->
          <div  v-if="simple" class="btn-group btn-group-sm" role="group" aria-label="...">
            <a :href="uriaction(btn.url,entry[btn.key])" :title="btn.action || btn.delete" v-for="btn in actions" v-bind:class="[ btn.delete ? 'btn btn-sm btn-danger' : 'btn btn-outline-primary btn-sm']" v-on:click.prevent="confimAction(btn,uriaction(btn.url,entry[btn.key]))">
            <i v-bind:class="btn.icons" aria-hidden="true"></i>
            </a>
          </div>
          <!-- END BUTTON IS SIMPLE -->
        </td>
      </tr>
    </tbody>
  </table>
</div>
</script>

