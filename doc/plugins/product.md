Product Plugin
==============

.. code-block:: php

    <?php

		$specTable = null;
		$specRenderer = new SpecRenderer( $id );
		if( $specRenderer->hasSpec() ) {
			$specTable = $specRenderer->render('product-spec.html');
		}


.. code-block:: htmldjango

    <table summary="Submitted table designs">
    <thead>
        <tr>
            {% for header in headers %}
                <th scope="col">{{ header }}</th>
            {% endfor %}
        </tr>
    </thead>
    <tbody>
        {% for spec in specs  %}
            {% if loop.index is odd %}
                <tr class="odd">
            {% else %}
                <tr class="even">
            {% endif %}
                {% for column in columns %}
                    <td>{{ spec[ column ] }}</td>
                {% endfor %}
            </tr>
        {% endfor %}
    </tbody>
    </table>
