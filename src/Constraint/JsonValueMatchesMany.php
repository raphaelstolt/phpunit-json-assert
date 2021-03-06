<?php
namespace Helmich\JsonAssert\Constraint;

use PHPUnit_Framework_Constraint as Constraint;

/**
 * Constraint that asserts that a JSON document matches an entire set of JSON
 * value constraints.
 *
 * @package    Helmich\JsonAssert
 * @subpackage Constraint
 */
class JsonValueMatchesMany extends Constraint
{

    /** @var JsonValueMatches[] */
    private $constraints = array();

    /**
     * JsonValueMatchesMany constructor.
     *
     * @param array $constraints A set of constraints. This is a key-value map
     *                           where each key is a JSON path expression,
     *                           associated with a constraint that all values
     *                           matched by that expression must fulfill.
     */
    public function __construct(array $constraints)
    {
        parent::__construct();

        foreach ($constraints as $key => $constraint) {
            if (!$constraint instanceof Constraint) {
                $constraint = new \PHPUnit_Framework_Constraint_IsEqual($constraint);
            }

            $this->constraints[] = new JsonValueMatches($key, $constraint);
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return implode(
            ' and ',
            array_map(
                function (Constraint $constraint) {
                    return $constraint->toString();
                },
                $this->constraints
            )
        );
    }

    /**
     * @inheritdoc
     */
    protected function matches($other)
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->evaluate($other, '', true)) {
                return false;
            }
        }
        return true;
    }
}
