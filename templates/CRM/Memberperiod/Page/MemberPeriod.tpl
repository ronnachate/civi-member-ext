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
            {if $accessContribution}
                <th scope="col">
                Contribution
                </th>
            {/if}
        </tr>
        </thead>
        
        {foreach from=$membershipPeriods item="membershipPeriod"}
        <tr>
            <td>{$membershipPeriod.start_date|crmDate}</td>
            {assign "end_date" "No expired date"}
            {if $membershipPeriod.end_date }
            {
                {assign "end_date" $membershipPeriod.end_date|crmDate}
            }
            <td>{$end_date}</td>
            <td>{$membershipPeriod.created_at|crmDate}</td>
            {if $accessContribution}
                <td>
                    {if $membershipPeriod.contribution}
                        <center>
                            {$membershipPeriod.contribution.total_amount|crmMoney:$membershipPeriod.contribution.currency}
                            <a href="{$contactViewUrl}&id={$membershipPeriod.contribution.id}" class="action-item crm-hover-button" title="View Contribution">View</a>
                        </center>
                    {/if}
                </td>
            {/if}
        </tr>
        {/foreach}
    </table>
{/strip}