{{ content() }}

{{ flashSession.output() }}

<br /><br />
<div class="container">
<p><a href="/labor/create/" class="btn btn-success">Create new information</a>
    <h2>Labor table</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Position</th>
            <th>CV-attachment</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        {% for labor in labors %}
        <tr>
            <th>{{ labor.id }}</th>
            <th>{{ labor.username }}</th>
            <th>{{ labor.position }}</th>
            {% set filesCount = labor.getLaborCV() | length %}
                            {% if filesCount > 0 %}
            <th><a href="/cv/{{ labor.getLaborCV()[0].filename }}" title="download in pdf format">
                    <img src="/pdf.png" alf="cv:{{ labor.username }}" id="pdf" align="middle" width=40 height=50 style="display:block; cursor:pointer;" />
                </a>
                            {% endif %}
            </th>
            <th><a href="/labor/update/{{ labor.id }}" title="have to change information" class="btn btn-success">Edit</a></th>
            <th><a href="/labor/delete/{{ labor.id }}" title="have to delete information" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a></th>
        </tr>
        {% endfor %}
        </tbody>
    </table>
</div>
