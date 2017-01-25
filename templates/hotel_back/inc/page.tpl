<div class="pagination  pagination-centered">
  <ul>
  <%section name=pn loop=$page%>
    <%if $smarty.section.pn.first%>
        <li<%if $page[pn].pn==''%> class="active"<%/if%>><a href="<%$page[pn].url%>">Prev</a></li>
    <%elseif $smarty.section.pn.last%>
        <li<%if $page[pn].pn==''%> class="active"<%/if%>><a href="<%$page[pn].url%>">Next</a></li>
     <%else%>
        <li<%if $page[pn].pn==$pn%> class="active"<%/if%>><a href="<%$page[pn].url%>"><%$page[pn].pn%></a></li>
    <%/if%>
  <%/section%>
  </ul>
</div>