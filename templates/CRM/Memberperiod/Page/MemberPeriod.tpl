{strip}
  <h3>Membership period history</h3>
  <table class="selector row-highlight">
    <thead class="sticky">
    <tr>
        <th scope="col">
           Start Date
        </th>
        <th scope="col">
           End Date
        </th>
        <th scope="col">
           Created Date
        </th>
    </tr>
    </thead>
    
    {foreach from=$membershipPeriods item="membershipPeriod"}
      <tr>
          <td>{$membershipPeriod.start_date|crmDate}</td>
          <td>{$membershipPeriod.end_date|crmDate}</td>
          <td>{$membershipPeriod.created_at|crmDate}</td>
      </tr>
    {/foreach}
    
  </table>
{/strip}